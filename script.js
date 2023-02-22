const app = Vue.createApp({
    data(){
        return {
            countVertex: 0,
            arrData: [],
            adjacencyMatrix: [],
            incidenceMatrix: {}
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
        },
        getResult: function()
        {
            let tmpData;
            this.adjacencyMatrix = [];
            this.incidenceMatrix = {};

            // заполняю матрицу начальными нулями
            for(let i = 0; i < this.countVertex; ++i)
            {
                this.adjacencyMatrix[i] = [];
                for(let j = 0; j < this.countVertex; ++j)
                {
                    this.adjacencyMatrix[i][j] = 0;
                    // this.incidenceMatrix[j +'-' + i][j] = 0;
                }
            }

            for(let i = 0; i < this.arrData.length; ++i)
            {
                tmpData = this.arrData[i].split(', ');

                tmpData.forEach(j => {
                    this.adjacencyMatrix[j - 1][i] = 1;

                    this.incidenceMatrix[`${j}-${i + 1}`] = {};

                    for(let k = 1; k <= this.countVertex; ++k){
                        this.incidenceMatrix[`${j}-${i + 1}`][k] = 0;
                    }

                    this.incidenceMatrix[`${j}-${i + 1}`][i + 1] = -1;
                    this.incidenceMatrix[`${j}-${i + 1}`][j] = 1;
                });
            }

            console.log(this.incidenceMatrix);
        },
        getAdjacencyMatrix: function() 
        {

        },
        getIncidenceMatrix: function()
        {

        }
    }
})

app.mount("#app")