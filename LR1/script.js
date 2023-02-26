const app = Vue.createApp({
    data(){
        return {
            countVertex: 0,
            selectedTab: 0,
            tabs: ['Матрица смежности А', 'Матрица инциденций В', 'ГРАФ'],
            arrData: [],
            adjacencyMatrix: [],
            incidenceMatrix: {},
            flag: false
        }
    },

    methods: {
        addDataInput: function() {
            if(this.countVertex < this.arrData.length){
                this.arrData = this.arrData.slice(0, this.countVertex);
            }

            for(let i = 0; i < this.countVertex; ++i)
            {
                if(typeof this.arrData[i] == 'undefined'){
                    this.arrData.push('');
                }
            }
            this.flag = false;
        },
        getResult: function()
        {
            let tmpData;
            let flagIncorrectData = false;
            this.adjacencyMatrix = [];
            this.incidenceMatrix = {};

            // заполняю матрицу начальными нулями
            for(let i = 0; i < this.countVertex; ++i)
            {
                this.adjacencyMatrix[i] = [];
                for(let j = 0; j < this.countVertex; ++j)
                {
                    this.adjacencyMatrix[i][j] = 0;
                }
            }

            for(let i = 0; i < this.arrData.length; ++i)
            {
                tmpData = this.arrData[i].split(', ');

                if(this.arrData[i] == '') continue;

                tmpData.forEach(j => {
                    if(isNaN(j) || (j <= 0 || j > this.countVertex)) {
                        flagIncorrectData = true;
                        return;
                    }

                    this.adjacencyMatrix[j - 1][i] = 1;

                    this.incidenceMatrix[`${j}-${i + 1}`] = {};

                    for(let k = 1; k <= this.countVertex; ++k)
                    {
                        this.incidenceMatrix[`${j}-${i + 1}`][k] = 0;
                    }

                    if(j == (i + 1)){
                        this.incidenceMatrix[`${j}-${i + 1}`][j] = 2;
                    } else {
                        this.incidenceMatrix[`${j}-${i + 1}`][i + 1] = -1;
                        this.incidenceMatrix[`${j}-${i + 1}`][j] = 1;
                    }
                });

                if(flagIncorrectData) {
                    this.flag = false;
                    break;
                }

                this.flag = true;
            }

            // сортировка для ребер
            if(this.flag) {
                this.incidenceMatrix = Object.keys(this.incidenceMatrix).sort().reduce(
                    (obj, key) => {
                      obj[key] = this.incidenceMatrix[key];
                      return obj;
                    },
                    {}
                );
                
                this.getGraph();
            }
        },
        getGraph()
        {
            let canvas = document.querySelector('#canvas'),
                context = canvas.getContext('2d');

            canvas.width = 640;
            canvas.height = 480;
            context.scale(1.5, 1.5); 

            let nodes = [];


            for(let i = 0; i < this.countVertex; ++i)
            {                
                nodes.push({
                    id: i,
                    x: Math.random() * 640 / 1.5,
                    y: Math.random() * 480 / 1.5,
                    childs: []
                });

                for(let j = 0; j < this.countVertex; ++j)
                {                
                    if(this.adjacencyMatrix[i][j] == 1)
                    {
                        nodes[i].childs.push(j);
                    }
                    // tmpData[i] = this.arrData[i].split(', ');
                }
                // tmpData[i] = this.arrData[i].split(', ');
            }

            nodes.forEach(function(node){
                createNodes(node, nodes);
            });
        }
    }
})

app.mount("#app")


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