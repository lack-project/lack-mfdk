<figure id="iloader">
    <div class="d-flex align-items-center m-0">
        <strong>Loading...</strong>
        <div class="spinner-border ml-auto" role="status" aria-hidden="true"></div>
    </div>
</figure>
<iframe id="icontent" hidden>
</iframe>


<style>
    iframe {

        width: 100%;
        background-color: transparent;
        border: 0px none transparent;
        padding: 0px;
        overflow: hidden;
    }
</style>
<script>

    (() => {
        let ifContent = document.getElementById("icontent");
        let ifLoader = document.getElementById("iloader");

        window.setInterval(() => {
            ifContent.style.height = ifContent.contentWindow.document.body.scrollHeight + 10 + "px";
        }, 500);
        ifContent.onload = () => {
            ifContent.hidden = false;
            ifLoader.hidden = true;
            ifContent.style.height = ifContent.contentWindow.document.body.scrollHeight + 10 + "px";

        }

        let getFragment = () => {
            let match = location.pathname;
            return match ? match : '';
        }
        let routeUpdate = (fragment) => {
            console.log("route update", fragment);
            ifLoader.hidden = false;

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
                    ifContent.hidden = true;
                    ifContent.setAttribute("src", route.target);
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
            if (typeof data !== "object")
                return;

            switch(data.type) {
                case "route":
                    window.history.pushState(null, data.path, data.path);
                    break;
                case "broadcast":
                    for (let iframe of document.getElementsByTagName("iframe")) {
                        iframe.postMessage(data, "*");
                    }
                    break;
            }
        });
    })();












</script>