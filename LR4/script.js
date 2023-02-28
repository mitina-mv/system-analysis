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
        getResult: function()
        {
            this.flag = true;

            for(let i = 0; i < this.countVertex; ++i)
            {
                for(let j = 0; j < this.countVertex; ++j)
                {
                    if(this.arrData[i][j] < 0){
                        this.flag = false;
                        break;
                    }
                }

                if(!this.flag) break;
            }

            if(this.flag)
            {                
                axios
                    .post('/LR4/getResult.php', {
                        graph: this.arrData,
                        startVertex: this.startVertex 
                    }
                    )
                    .then(response => {
                        this.lastMatrix = response.data.lastMatrix;
                        this.newMatrix = response.data.newMatrix;
                        this.levels = response.data.levels;
                        this.namesVertex = response.data.namesVertex;
                    })
                    .catch(error => console.log(error));
            }
        },
        getString: function(obj) {
            return Object.keys(obj).map(v => Number(v) + 1).join(', ');
        }
    }
})

app.mount("#app")