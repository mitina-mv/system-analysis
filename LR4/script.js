const app = Vue.createApp({
    data(){
        return {
            countVertex: 0,
            startVertex: 1,
            selectedTab: 0,
            tabs: ['Матрица кратчайших путей', 'Остовные деревья'],
            arrData: [],
            flag: false,
            tree: [],
            matrixPath: []
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
                    // if(i !== j)
                        this.arrData[i][j] = 0;
                    // else
                        // this.arrData[i][j] = '';
                }
            }

            this.flag = false;
        },
        getResult: function()
        {
            this.flag = true;
            this.tree = [];
            this.matrixPath = [];

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
                        graph: this.arrData
                    }
                    )
                    .then(response => {
                        response.data.forEach(element => {
                            this.tree.push(element.tree);
                            this.matrixPath.push(element.path);
                        });
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