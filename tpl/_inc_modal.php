<div id="modal1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <figure>
                <div class="d-flex align-items-center m-5">
                    <strong>Loading...</strong>
                    <div class="spinner-border ml-auto" role="status" aria-hidden="true"></div>
                </div>
            </figure>
            <iframe style="border-radius: inherit;border: none;" frameborder="0" src="" sandbox="allow-scripts"></iframe>
        </div>
    </div>
</div>
<script>

    (()=>{
        let modalDiv = document.getElementById("modal1");
        let modal = null;
        let iframe2 = modalDiv.getElementsByTagName("iframe")[0];
        let loader = modalDiv.getElementsByTagName("figure")[0];

        let openModal = (src) => {
            iframe2.hidden = true;
            loader.hidden = false;

            iframe2.onload = (e) => {
                iframe2.hidden = false;
                loader.hidden = true;
            }


            iframe2.setAttribute("src", src);
            if (modal === null) {
                modal = new coreui.Modal(modalDiv);
                modalDiv.addEventListener("hide.coreui.modal", () => {
                    iframe2.setAttribute("src", "");
                });
            }
            modal.show();
        }


        window.addEventListener("message", (event) => {
            if (typeof event.data !== "object" )
                return;
            switch (event.data.type) {
                case "modal_open":
                    console.log("[MK event]: modal_open src=", event.data.src);
                    openModal(event.data.src);
                    break;
                case "modal_close":
                    console.log("[MK event]: modal_close");
                    modal.hide();
                    break;
            }
        });
    })();



</script>