<template>
  <div id="main">
        <div class="topButton">
            <Button type="primary" @click="addManager" class="addBtn">新增</Button>
            <!-- <Button type="error">批量删除</Button> -->
            <Form class="searchBox">
                <Input type="text" v-model="search" placeholder="Search..." icon="ios-search" />
            </Form>
        </div>
        <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" 
                v-if="showForm" :label-width="100"  inline>
            <FormItem label="管理员账号" prop="manager_id">
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
                <Button @click="handleCancel()" style="margin-left: 8px">取消</Button>
            </FormItem>
        </Form>
        <!-- <Table border :columns="columns" :data="data">
           <template slot-scope="{ row }" slot="name"> 
                <strong>{{ row.manager_id }}</strong>
            </template>
            <template slot-scope="{ row, index }" slot="action">
                <Button type="primary" size="small" style="margin-right: 5px" @click="show(index)">查看</Button>
                <Button type="primary" size="small" @click="edit(index)">编辑</Button>
                <Button type="error" size="small" @click="remove(index)">删除</Button>
            </template>
        </Table> -->
         <Table
            :data="tableData"
            :columns="columns"
            border
        ></Table>
        <br>

  </div>
</template>

<script>
export default {
    data(){
        return{
            msg: '',
            search:'',
            showForm: false,//增加信息的表单
            userToken:'',
            data: [],
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
            // columns: [
            //     {
            //         title: '管理员ID',
            //         key: 'manager_id'
            //     },{
            //         title: '姓名',
            //         key: 'name'
            //     },{
            //         title: '电话号码',
            //         key: 'telephone'
            //     },{
            //         title: 'Action',
            //         slot: 'action',
            //         width: 150,
            //         align: 'center'
            //     }
            // ],
            
            editTableIndex: -1,
            editId: '',
            editName: '',
            editTelephone: '',
            columns:[
            {
                title: '管理员账号',
                key: 'manager_id',
                // id不可更改
                // render: (h, { row, index }) => {
                // return this.renderTableColumn('editId', 'manager_id', h, { row, index })
                // }
            },
            {
                title: '姓名',
                key: 'name',
                render: (h, { row, index }) => {
                return this.renderTableColumn('editName', 'name', h, { row, index })
                }
            },
            {
                title: '联系方式',
                key: 'telephone',
                render: (h, { row, index }) => {
                return this.renderTableColumn('editTelephone', 'telephone', h, { row, index })
                }
            },
            {
                title: '操作',
                render: (h, { row, index }) => {
                    if (this.editTableIndex === index) {
                        return [
                            h('Button', {
                                props: {
                                    type: 'primary'
                                },
                                style: {
                                    marginLeft: '30px'
                                },
                                on: {
                                click: () => {
                                    //id不可更改
                                    this.data[index].manager_id = this.data[index].manager_id
                                    this.data[index].name = this.editName
                                    this.data[index].telephone = this.editTelephone
                                    this.editTableIndex = -1
                                    // 在此处配置异步提交
                                    fetch("api/bookms/server/manager/edit.php",{
                                        method:"POST",
                                        headers:{
                                            'Accept': 'application/json',
                                            'Content-Type': 'application/json',
                                        },
                                        body:JSON.stringify({
                                            // username:this.data[index],
                                            manager_id:this.data[index].manager_id,
                                            name:this.data[index].name,
                                            telephone:this.data[index].telephone,
                                        }),
                                        }).then(res=> { 
                                            return res.json();
                                        }).then(res=>{
                                        if(res[0].code == 1){
                                            this.$Message.success(res[0].message);
                                        }else{
                                            this.$Message.error(res[0].message);
                                        }
                                    });
                                }
                                }
                            }, '保存'),
                            h('Button', {
                                style: {
                                    marginLeft: '30px'
                                },
                                on: {
                                    click: () => {
                                        this.editTableIndex = -1
                                    }
                                }
                            }, '取消')
                        ]
                    } else {
                        return [
                            h('Button', {
                                props: {
                                    type: 'primary'
                                },
                                style: {
                                    marginLeft: '30px'
                                },
                                on: {
                                    click: () => {
                                    // this.editName = row.name
                                    // this.editName = row.code
                                    // this.editRemark = row.remark
                                    this.editTableIndex = index
                                    }
                                }
                            }, '修改'),
                            h('Button', {
                                props: {
                                    type: 'error'
                                },
                                style: {
                                    marginLeft: '30px'
                                },
                                on: {
                                    click: () => {
                                    this.$Modal.confirm({
                                        title: '提示',
                                        content: '确认要删除该行吗？',
                                        onOk: () => {
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
                                        },
                                        onCancel: () => {
                                            this.$Message.info('取消删除!')
                                        }
                                    })
                                }
                            }
                            }, '删除'), 
                        ]
                    }
                }
            }
            ],
        }
        
    },
    mounted(){
        this.showList();
        // this.totalNum();
    },
    computed:{
        tableData(){
            const search=this.search;
            if(search){
                // filter() 方法创建一个新的数组，新数组中的元素是通过检查指定数组中符合条件的所有元素。
                // 注意： filter() 不会对空数组进行检测。
                // 注意： filter() 不会改变原始数组。
                return this.data.filter(data => {
                    // some() 方法用于检测数组中的元素是否满足指定条件;
                    // some() 方法会依次执行数组的每个元素：
                    // 如果有一个元素满足条件，则表达式返回true , 剩余的元素不会再执行检测;
                    // 如果没有满足条件的元素，则返回false。
                    // 注意： some() 不会对空数组进行检测。
                    // 注意： some() 不会改变原始数组。
                    return Object.keys(data).some(key => {
                    //Object.keys()---如果处理对象，将返回一个可进行枚举的属性数组，注意，是返回一个数组，是通过属性名组成的数组
                    //Object.keys()---参数：要返回其枚举自身属性的对象；  返回值：一个表示给定对象的所有可枚举属性的字符串数组
                    // indexOf() 返回某个指定的字符在某个字符串中首次出现的位置，如果没有找到就返回-1；
                    // 该方法对大小写敏感！所以之前需要toLowerCase()方法将所有查询到内容变为小写。
                        return String(data[key]).toLowerCase().indexOf(search) > -1
                    })
                })
            }
            return this.data
        }
    },
    methods:{
        showList(){
            fetch('api/bookms/server/manager/showList.php',{
                method:"POST",
                headers:{
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body:JSON.stringify({
                    Authorization:localStorage.getItem('Authorization'),//token
                }),
            }).then((res)=>{
                return res.json();
            }).then((res)=>{
                if(res[2].code==1){
                    this.data = res[0];
                    this.totalNum = res[1];
                }else{
                    // alert(res[2].message);
                    this.$Message.error(res[2].message);
                    this.$router.push('./index');
                }
            });
            // console.log(1111)//#########有输出 列表不更新
        },
        handleSubmit (name) {
            this.$refs[name].validate((valid) => {
                if (valid) {
                    var info = {
                        manager_id:this.formValidate.manager_id,
                        name:this.formValidate.name,
                        password:this.formValidate.password,
                        telephone:this.formValidate.telephone,
                    }
                    fetch("api/bookms/server/manager/add.php",{
                        method:"POST",
                        headers:{
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body:JSON.stringify({
                            Authorization:localStorage.getItem('Authorization'),//token
                            manager_id:this.formValidate.manager_id,
                            name:this.formValidate.name,
                            password:this.formValidate.password,
                            telephone:this.formValidate.telephone,
                        }),
                        }).then(res=> { 
                            return res.json();
                        }).then(res=>{
                            if(res[1].code==1){
                                if(res[0].code == 1){
                                    this.$Message.success(res[0].message);
                                    this.$refs[name].resetFields();                           
                                    this.data.push(info);
                                }else{
                                    this.$Message.error(res[0].message);
                                }
                            }else{
                                this.$Message.error(res[1].message);
                                this.$router.push('./index');
                            }
                        });
                } else {
                    this.$Message.error('Fail!');
                }
            })
        },
        handleCancel(){
            this.showForm = false;
        },
        handleReset (name) {
            this.$refs[name].resetFields();
        },
        addManager(){
            this.showForm = true;
        },
        // show (index) {
        //     this.$Modal.info({
        //         title: 'Manager Info',
        //         content: `编号：${this.data[index].manager_id}<br>姓名：${this.data[index].name}<br>电话号码：${this.data[index].telephone}`
        //     })
        // },
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
                    Authorization:localStorage.getItem('Authorization'),
                    manager_id:this.data[index].manager_id,
                }),
                }).then(res=> { 
                    return res.json();
                }).then(res=>{
                if(res[1].code==1){
                    if(res[0].code == 1){
                        this.$Message.success(res[0].message);
                    }else{
                        this.$Message.error(res[0].message);
                    }
                }else{
                    this.$Message.error(res[1].message);
                    this.$router.push('./index');
                }
            });
        },
        renderTableColumn (editname, columnName, h, { row, index }) {
            let edit
            if (this.editTableIndex === index) {
                this[editname] = row[columnName]
                edit = [h('Input', {
                props: {
                    value: row[columnName]
                },
                on: {
                    input: (val) => {
                    this[editname] = val
                    }
                }
                })]
            } else {
                edit = row[columnName]
            }
            return h('div', [edit])
        },
    },
}
</script>

<style scoped>
.topButton{
    margin-bottom: 20px;
    display: flex;
    justify-content:space-between;
}
.searchBox{
    width:300px;
}
</style>
