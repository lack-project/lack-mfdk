

class RouteChange {

}


class Mfdk {

    static map = {};


    static message(service, message)
    {
        window.postMessage(message, "*");
    }


}

Mfdk.map = {
    default: [
        "http://localhost",
        "https://dash.infracamp.org"
    ]
}


