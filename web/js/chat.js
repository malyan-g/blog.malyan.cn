/**
 * Created by M on 17/11/1.
 */
var wsServer = 'ws://127.0.0.1:9098';
var websocket = new WebSocket(wsServer);

websocket.onopen = function (evt) {
    console.log("Connected to WebSocket server.");
};

websocket.onclose = function (evt) {
    console.log("Disconnected");
};

websocket.onmessage = function (evt) {
    pushMsg(evt.data);
};

websocket.onerror = function (evt, e) {
    console.log('Error occured: ' + evt.data);
};

function sendMsg(){
    var msg = document.getElementById('msg');
    websocket.send(msg.value);
    pushMsg(msg.value)
    msg.value = '';
}

function pushMsg(msg) {
    var box = document.getElementById('msg-list');
    var child = document.createElement("p");
    child.innerHTML = msg;
    box.appendChild(child);
}