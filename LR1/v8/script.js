const app = Vue.createApp({
    data(){
        return {
            countVertex: 0,
            arrData: [],
            adjacencyMatrix: [],
            incidenceLeft: [],
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
            this.incidenceLeft = [];

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
                    if(isNaN(j) || (Number(j) <= 0 || Number(j) > this.countVertex)) {
                        flagIncorrectData = true;
                        return;
                    }

                    this.adjacencyMatrix[i][j - 1] = 1;
                });

                if(flagIncorrectData) {
                    this.flag = false;
                    break;
                }

                this.flag = true;
            }

            // по матрице смежности собираем множество левых инциденций
            for(let i = 0; i < this.countVertex; ++i)
            {
                this.incidenceLeft.push([]);
                for(let j = 0; j < this.countVertex; ++j)
                {
                    if(this.adjacencyMatrix[j][i] == 1){
                        this.incidenceLeft[i].push(j);
                    }
                }
            }
            console.log(this.incidenceLeft);
        },
        getString: function(arr) 
        {
            return arr.map(v => Number(v) + 1).join(', ');
        },
        remove(){
            this.countVertex = 0;
            this.flag = false;
            this.arrData = [];
        }
    }
})

app.mount("#app")