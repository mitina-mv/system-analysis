const app = Vue.createApp({
    data(){
        return {
            countVertex: 0,
            arrData: [],
            adjacencyMatrix: [],
            incidenceMatrix: [],
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
            this.incidenceMatrix = [];
            let tmpIncidenceMatrix = {};

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

                    tmpIncidenceMatrix[`${j}-${i + 1}`] = {};

                    for(let k = 1; k <= this.countVertex; ++k)
                    {
                        tmpIncidenceMatrix[`${j}-${i + 1}`][k] = 0;
                    }

                    if(j == (i + 1)){
                        tmpIncidenceMatrix[`${j}-${i + 1}`][j] = 2;
                    } else {
                        tmpIncidenceMatrix[`${j}-${i + 1}`][i + 1] = -1;
                        tmpIncidenceMatrix[`${j}-${i + 1}`][j] = 1;
                    }
                });

                if(flagIncorrectData) {
                    this.flag = false;
                    break;
                }

                this.flag = true;
            }

            if(this.flag) {
                // сортировка для ребер
                tmpIncidenceMatrix = Object.keys(tmpIncidenceMatrix).sort().reduce(
                    (obj, key) => {
                      obj[key] = tmpIncidenceMatrix[key];
                      return obj;
                    },
                    {}
                );

                for(let edge in tmpIncidenceMatrix)
                {
                    this.incidenceMatrix.push(tmpIncidenceMatrix[edge]);
                }
            }
        },
        remove(){
            this.countVertex = 0;
            this.flag = false;
            this.arrData = [];
        }
    }
})

app.mount("#app")