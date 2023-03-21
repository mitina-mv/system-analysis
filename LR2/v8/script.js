const app = Vue.createApp({
    data(){
        return {
            countVertex: 0,
            arrData: [],
            newIncidenceLeft: [],
            levels: [],
            namesVertex: {},
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
            if(this.countVertex <= 0) return;
            
            let tmpData = {};
            let flagIncorrectData = false;
            this.flag = true;

            this.newIncidenceLeft = [];
            this.levels = [];
            this.namesVertex = {};

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
            }

            if(this.flag)
            {                
                axios
                    .post('./getResult.php', tmpData)
                    .then(response => {
                        this.newIncidenceLeft = response.data.newSet;
                        this.levels = response.data.levels;
                        this.namesVertex = response.data.namesVertex;
                    })
                    .catch(error => console.log(error));
            }
        },
        getString: function(obj) {
            return Object.keys(obj).map(v => Number(v) + 1).join(', ');
        },
        remove(){
            this.countVertex = 0;
            this.flag = false;
            this.arrData = [];
        }
    }
})

app.mount("#app")