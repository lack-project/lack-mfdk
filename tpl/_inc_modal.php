<div id="modal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div id="modal1-loader">
                <div class="d-flex align-items-center m-5">
                    <strong>Loading...</strong>
                    <div class="spinner-border ml-auto" role="status" aria-hidden="true"></div>
                </div>
            </div>
            <iframe id="modal1-if" style="border-radius: inherit;border: none;" frameborder="0"></iframe>
        </div>
    </div>
</div>
<script>
    let modal = null;

    function modal_open(src) {
        if (modal === null)
            modal = new coreui.Modal(document.getElementById("modal1"));
        let iframe = document.getElementById("modal1-if");
        iframe.style.height = "0px";
        document.getElementById("modal1-loader").style.display = "block";
        iframe.onload = () => {
            window.setTimeout(() => {
                iframe.style.height = iframe.contentWindow.document.body.scrollHeight + "px";
                document.getElementById("modal1-loader").style.display = "none";
            }, 100);
        }
        iframe.setAttribute("src", event.data.src);
        modal.show();
    }

    window.addEventListener("message", (event) => {
        if (typeof event.data !== "object" )
            return;
        switch (event.data.type) {
            case "modal_open":
                console.log("[MK event]: modal_open src=", event.data.src);
                modal_open(event.data.src);
                break;
            case "modal_close":
                console.log("[MK event]: modal_close");
                modal.hide();
                break;
        }
    });
</script>