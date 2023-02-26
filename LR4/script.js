const app = Vue.createApp({
    data(){
        return {
            countVertex: 0,
            startVertex: 1,
            selectedTab: 0,
            tabs: ['Исходная матрица смежности', 'Новая матрица смежности', 'Иерархические уровни'],
            arrData: [],
            flag: false
        }
    },

    methods: {
        addDataInput: function() {
            this.arrData = [];
            
            for(let i = 0; i < this.countVertex; ++i)
            {
                this.arrData.push([]);
                for(let j = 0; j < this.countVertex; ++j)
                {
                    this.arrData[i][j] = 0;
                }
            }

            this.flag = false;
        },
        /* getResult: function()
        {
            let tmpData = {};
            let flagIncorrectData = true;

            this.lastMatrix = [];
            this.newMatrix = [];
            this.levels = [];

            // получение входных данных
            for(let i = 0; i < this.arrData.length; ++i)
            {                
                tmpData[i] = this.arrData[i].split(', ');

                if(this.arrData[i] == '')
                {
                    tmpData[i] = [];
                    continue;
                } 

                flagIncorrectData = tmpData[i].every(j => {
                    if(isNaN(j) || (Number(j) <= 0 || Number(j) > this.countVertex))
                    {
                        return false;
                    } 
                    else return true;
                });

                if(!flagIncorrectData) {
                    this.flag = false;
                    break;
                }

                this.flag = true;
            }

            if(this.flag)
            {                
                axios
                    .post('/LR2/getResult.php', tmpData)
                    .then(response => {
                        this.lastMatrix = response.data.lastMatrix;
                        this.newMatrix = response.data.newMatrix;
                        this.levels = response.data.levels;
                        this.namesVertex = response.data.namesVertex;
                    })
                    .catch(error => console.log(error));
            }
        }, */
        getString: function(obj) {
            return Object.keys(obj).map(v => Number(v) + 1).join(', ');
        }
    }
})

app.mount("#app")