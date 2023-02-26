<!DOCTYPE HTML>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Nodes</title>
    <style>
        #canvas {
            display:block;
            margin:auto;
            width:640px;
            height:480px;
            background-color:#fff;
        }
    </style>
</head>
<body>
    <canvas id="canvas"></canvas>
    <script>
        var canvas = document.getElementById('canvas'),
        context = canvas.getContext('2d');
        canvas.width = 640;
        canvas.height = 480;
        context.scale(1.5, 1.5); 
        
var nodes = [
            {id : 1, x : 325 / 1.5, y : 100 / 1.5, text : '1', childs : [2, 3, 4, 5]},
            {id : 2, x : 250 / 1.5, y : 200 / 1.5, text : '2', childs : [11, 12]},
            {id : 3, x : 300 / 1.5, y : 200 / 1.5, text : '3'},
            {id : 4, x : 350 / 1.5, y : 200 / 1.5, text : '4'},
            {id : 5, x : 400 / 1.5, y : 200 / 1.5, text : '5', childs : [6,7,8,9,10]},
            {id : 6, x : 300 / 1.5, y : 300 / 1.5, text : '6'},
            {id : 7, x : 350 / 1.5, y : 300 / 1.5, text : '7'},
            {id : 8, x : 400 / 1.5, y : 300 / 1.5, text : '8', childs: [2, 4]},
            {id : 9, x : 450 / 1.5, y : 300 / 1.5, text : '9'},
            {id : 10, x : 500 / 1.5, y : 300 / 1.5, text : '10'},
            {id : 11, x : 200 / 1.5, y : 300 / 1.5, text : '11'},
            {id : 12, x : 250 / 1.5, y : 300 / 1.5, text : '123'},
];
 
 
nodes.forEach(function(node){
    createNodes(node, nodes);
}); 
 
function createNodes(params, nodes){
 
    if(params.childs && params.childs.length)
    {
        params.childs.forEach(function(id)
        {
            if(params.id != id){
                let child = getNodeById({id : id, nodes : nodes});
                
                context.beginPath();
                canvas_arrow(context, params.x, params.y, child.x, child.y - 10);
                context.stroke();
                createCircle(context, params.x, params.y, 10, params.text);
                createCircle(context, child.x, child.y, 10, child.text);            
            }
        });
    }
} 
 
function getNodeById(params){
    var result;
    params.nodes.forEach(function(node){
        if(node.id == params.id){
            result = node;
        }
    });
    return result;
}

function canvas_arrow(context, fromx, fromy, tox, toy) {
    var headlen = 10; // length of head in pixels
    var dx = tox - fromx;
    var dy = toy - fromy;
    var angle = Math.atan2(dy, dx);
    context.moveTo(fromx, fromy);
    context.lineTo(tox, toy);
    context.lineTo(tox - headlen * Math.cos(angle - Math.PI / 6), toy - headlen * Math.sin(angle - Math.PI / 6));
    context.moveTo(tox, toy);
    context.lineTo(tox - headlen * Math.cos(angle + Math.PI / 6), toy - headlen * Math.sin(angle + Math.PI / 6));
}

function createCircle(context, x, y, radius, text){
    context.beginPath();
    context.arc(x, y, radius, 0, 2 * Math.PI, false);
    context.fillStyle = '#ff5121';
    context.fill();
    context.lineWidth = 1;
    context.strokeStyle = '#363636';
    context.stroke();
    context.fillStyle = "#fff";
    if(text < 10)
    {
        context.fillText(text, x - (radius * 25 / 100), y + (radius * 25 / 100));
    } else if(text < 100)
    {
        context.fillText(text, x - (radius * 55 / 100), y + (radius * 25 / 100));
    } else if(text < 1000)
    {
        context.fillText(text, x - (radius * 85 / 100), y + (radius * 25 / 100));
    } 
    context.stroke();
}
        
        
    </script>

</body>
</html>