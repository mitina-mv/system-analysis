const app = Vue.createApp({
    data(){
        return {
            countVertex: 0,
            selectedTab: 0,
            tabs: ['Матрица смежности А', 'Матрица инциденций В'],
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
                    if(isNaN(j) || (Number(j) <= 0 || Number(j) > this.countVertex))
                    {
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
            }
        },
    }
})

app.mount("#app")