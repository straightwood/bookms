<template>
  <div id="main">
        <div class="topButton">
            <Button type="primary">新增</Button>
            <Button type="error">删除</Button>
            <Button >查找</Button>
        </div>
        <Table border :columns="columns12" :data="data6">
           <template slot-scope="{ row }" slot="name"> <!-- ############## -->
                <strong>{{ row.manager_id }}</strong>
            </template>
            <template slot-scope="{ row, index }" slot="action">
                <Button type="primary" size="small" style="margin-right: 5px" @click="show(index)">View</Button>
                <Button type="error" size="small" @click="remove(index)">Delete</Button>
            </template>
        </Table>
  </div>
</template>

<script>
export default {
    data(){
        return{
            columns12: [
                {
                    title: '管理员ID',
                    key: 'manager_id'
                },{
                    title: '姓名',
                    key: 'name'
                },{
                    title: '电话号码',
                    key: 'telephone'
                },{
                    title: 'Action',
                    slot: 'action',
                    width: 150,
                    align: 'center'
                }
            ],
            data6: [
                // {
                //     name: 'testid1',
                //     age: '管理员1',
                //     address: '15355470000'
                // },{
                //     name: 'John Brown',
                //     age: 18,
                //     address: 'New York No. 1 Lake Park'
                // },{
                //     name: 'Jim Green',
                //     age: 24,
                //     address: 'London No. 1 Lake Park'
                // },{
                //     name: 'Joe Black',
                //     age: 30,
                //     address: 'Sydney No. 1 Lake Park'
                // },{
                //     name: 'Jon Snow',
                //     age: 26,
                //     address: 'Ottawa No. 2 Lake Park'
                // }
            ]
        }
        
    },
    mounted(){
        this.show();
    },
    methods:{
        show(){
            fetch('api/bookms/server/manager/showList.php',{
            }).then((res)=>{
                return res.json();
            }).then((res)=>{
                this.data6 = res;
                console.log(this.data6) // res是最终的结果
            })
        },
        // show (index) {
        //     this.$Modal.info({
        //         title: 'User Info',
        //         content: `Name：${this.data6[index].name}<br>Age：${this.data6[index].age}<br>Address：${this.data6[index].address}`
        //     })
        // },
        remove (index) {
            this.data6.splice(index, 1);
            // console.log(this.data6.splice(index, 1)[0].name);
        }
    },
}
</script>

<style scoped>
.topButton{
    margin-bottom: 20px;
}
</style>
