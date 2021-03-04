

class MF {

    static message(message)
    {
        parent.postMessage(message, "*");
    }

    static modalOpen(src) {
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

}


