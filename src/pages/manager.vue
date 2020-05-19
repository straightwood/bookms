<template>
  <div id="main">
        <div class="topButton">
            <Button type="primary" @click="addManager">新增</Button>
            <Button type="error">删除</Button>
            <Button >查找</Button>
        </div>
        <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" 
                v-if="showForm" :label-width="100"  inline>
            <FormItem label="管理员编号" prop="manager_id">
                <Input v-model="formValidate.manager_id" placeholder="Enter manager_id" />
            </FormItem>
            <FormItem label="姓名" prop="name">
                <Input v-model="formValidate.name" placeholder="Enter name" />
            </FormItem>
            <FormItem label="初始密码" prop="password">
                <Input v-model="formValidate.password" placeholder="Enter password" />
            </FormItem>
            <FormItem label="联系方式" prop="telephone">
                <Input v-model="formValidate.telephone" placeholder="Enter telephone" />
            </FormItem>
            <FormItem>
                <Button type="primary" @click="handleSubmit('formValidate')">确定</Button>
                <Button @click="handleReset('formValidate')" style="margin-left: 8px">重置</Button>
            </FormItem>
        </Form>
        <Table border :columns="columns" :data="data">
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
            showForm: false,
            formValidate: {
                manager_id: '',
                name: '',
                password: '',
                telephone: '',
            },
            ruleValidate: {
                manager_id: [
                    { required: true, message: 'The id cannot be empty', trigger: 'blur' }
                ],
                name: [
                    { required: true, message: 'The name cannot be empty', trigger: 'blur' },
                ],
                password: [
                    { required: true, message: 'The password cannot be empty', trigger: 'blur' }
                ],
                telephone: [
                    { required: true, message: 'The telephone cannot be empty', trigger: 'blur' },
                ],
            },
            columns: [
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
            data: [
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
        this.showList();
    },
    methods:{
        showList(){
            fetch('api/bookms/server/manager/showList.php',{
            }).then((res)=>{
                return res.json();
            }).then((res)=>{
                this.data = res;
                // console.log(this.data) // res是最终的结果
            })
        },
        handleSubmit (name) {
            this.$refs[name].validate((valid) => {
                if (valid) {
                    // this.$Message.success('Success!');
                    fetch("api/bookms/server/manager/add.php",{
                        method:"POST",
                        headers:{
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body:JSON.stringify({
                            // username:this.data[index],
                            manager_id:this.formValidate.manager_id,
                            name:this.formValidate.name,
                            password:this.formValidate.password,
                            telephone:this.formValidate.telephone,
                        }),
                        }).then(res=> { 
                            return res.json();
                        }).then(res=>{
                            if(res[0].code == 1){
                                this.$Message.success(res[0].message);//############操作后提示
                                this.$refs[name].resetFields();
                            }else{
                                this.$Message.error(res[0].message);
                            }
                        });
                } else {
                    this.$Message.error('Fail!');
                }
            })
        },
        handleReset (name) {
            this.$refs[name].resetFields();
        },
        addManager(){
            this.showForm = true;
        },
        show (index) {
            this.$Modal.info({
                title: 'Manager Info',
                content: `编号：${this.data[index].manager_id}<br>姓名：${this.data[index].name}<br>电话号码：${this.data[index].telephone}`
            })
        },
        remove (index) {
            // this.data.splice(index, 1);
            // console.log(this.data.splice(index, 1)[0].name);
            // console.log(this.data[index].manager_id);
            fetch("api/bookms/server/manager/delete.php",{
                method:"POST",
                headers:{
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body:JSON.stringify({
                    // username:this.data[index],
                    manager_id:this.data[index].manager_id,
                }),
                }).then(res=> { 
                    return res.json();
                }).then(res=>{
                if(res[0].code == 1){
                    this.$Message.success(res[0].message);
                    this.data.splice(index, 1);
                }else{
                    this.$Message.error(res[0].message);
                }
            });
        }
    },
}
</script>

<style scoped>
.topButton{
    margin-bottom: 20px;
}
</style>
