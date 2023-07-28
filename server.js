const express = require('express');
const app = express();

const server = require('http').createServer(app);

const io = require('socket.io')(server, {
    cors:  {origin: "*"}
});

io.on('connection', (socket)=>{
    console.log('connection');
    socket.on('sendChatToMyServer', (data) => {
        const roomId = data.roomId;
        const message = data.message;
        const id1 = data.id1;
        const uuid = data.uuid;
        console.log('socketId'+socket.id);
        console.log(`Received roomId: ${roomId}`);
        console.log(`Received message: ${message}`);
        console.log(`id1: ${id1}`);
        console.log(`uuid: ${uuid}`);
        const clientId = 'sendChatToMyClient'+roomId;
        io.sockets.emit(clientId,  {  message: message ,current:id1,uuid:uuid});
        // socket.broadcast.emit('sendChatToMyClient',message);
    });

    // socket.emit();
    //on means sending the message and emit means recieving the message
    socket.on('disconnect', (socket)=>{
            console.log('Disconnect');
    });
})


const PORT = 3000;
server.listen(PORT,()=>{
    console.log(`server listening on port ${PORT}`);
});
