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

    let feConfig = <?php echo phore_json_pretty_print(phore_json_encode($__DATA)); ?>;

    (() => {
        window.addEventListener("message", (event) => {
            let data = event.data;
            if (typeof data !== "object")
                return;

            switch(data.type) {
                case "broadcast":
                    for (let iframe of document.getElementsByTagName("iframe")) {
                        iframe.postMessage(data, "*");
                    }
                    break;
            }
        });
    })();


</script>

<?php echo file_get_contents(__DIR__ . "/_inc_router.php"); ?>
<?php echo file_get_contents(__DIR__ . "/_inc_modal.php"); ?>