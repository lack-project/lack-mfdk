

<iframe id="icontent" src="">
    Loading...
</iframe>

<button onclick="document.getElementById('icontent').contentWindow.postMessage('main1', '*')">Click me</button>
<style>
   iframe {
        border: 1px;
        width: 100%;
    }
</style>
<script>

    let feConfig = <?php echo phore_json_pretty_print(phore_json_encode($__DATA)); ?>;

    let icontent = document.getElementById("icontent");
    icontent.onload = () => {
        icontent.style.height = icontent.contentWindow.document.body.scrollHeight + 10 + "px";
        icontent.style.visibility = "visible";
    }


    function getFragment () {
        let match = location.pathname;
        return match ? match : '';
    }

    function routeUpdate(fragment) {
        console.log("route update", fragment);
        // Update css :active selector
        for (let elem of document.getElementsByTagName("a")) {
            elem.classList.remove("c-active");
            if (fragment === elem.getAttribute("href")) {
                console.log(elem);
                elem.classList.add("c-active");
            }
        }
        for(let route of feConfig.routes) {
            if (fragment === route.route) {
                icontent.style.visibility = "hidden";
                icontent.setAttribute("src", route.target);
                return;
            }
        }


    }

    let curRoute = null;
    window.setInterval(() => {
        if (curRoute === getFragment())
            return;
        curRoute = getFragment();
        routeUpdate(curRoute);
    }, 50);


    for(let elem of document.getElementsByTagName("a")) {
        elem.onclick = (e) => {
            window.history.pushState(null, null, elem.href);
            e.stopPropagation();
            return false;
        }
    }

    window.addEventListener("message", (event) => {
        let data = event.data;

        console.log("main", event);
    });

</script>