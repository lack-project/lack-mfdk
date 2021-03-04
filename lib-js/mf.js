

class MF {

    static getOptions()
    {
        let match = window.location.href.match(/#(.*)$/);
        let fragment = match[1] ? match[1] : '';
        return JSON.parse(decodeURIComponent(fragment));
    }

    static message(message)
    {
        parent.postMessage(message, "*");
    }

    static modalOpen(src, params) {
        if (typeof params !== "undefined")
            src += '#' + JSON.stringify(params);
        this.message({type: "modal_open", src: src});
    }

    static modalClose() {
        this.message({type: "modal_close"});
    }

    static route(path) {
        this.message({type: "route", path: path});
    }

    static broadcast(name, data) {
        this.message({type: "broadcast", name: name, data: data});
    }

    static adjust_frame_height(height) {
        this.message({type: "adjust_frame_height", height: height, path: location.pathname + location.hash});
    }

}

(() => {
    let curHeight = 0;
    window.setInterval(() => {
        if (document.body.scrollHeight === curHeight)
            return;
        curHeight = document.body.scrollHeight;
        MF.adjust_frame_height(curHeight);
    }, 100)
})();



