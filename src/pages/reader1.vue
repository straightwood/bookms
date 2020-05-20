<template>
  <div id="main">
        <div class="topButton">
            <Button type="primary" @click="addReader" class="addBtn">新增</Button>
            <!-- <Button type="error">批量删除</Button> -->

            <Form ref="formSearch" :model="formSearch" inline class="serchBox">
                <FormItem prop="content">
                    <Input type="text" v-model="search" placeholder="Search..." icon="ios-search" />
                </FormItem>
            </Form>
        </div>
        <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" 
                v-if="showForm" :label-width="100"  inline>
            <FormItem label="读者编号" prop="reader_number">
                <Input v-model="formValidate.reader_number" placeholder="Enter reader_number" />
            </FormItem>
            <FormItem label="姓名" prop="name">
                <Input v-model="formValidate.name" placeholder="Enter name" />
            </FormItem>
            <FormItem label="所属学院" prop="department">
                <Input v-model="formValidate.department" placeholder="Enter department" />
            </FormItem>
            <FormItem label="性别" prop="gender">
                <!-- <Input v-model="formValidate.telephone" placeholder="Enter gender" /> -->
                <Select v-model="formValidate.gender" style="width:200px">
                  <Option v-for="item in genderList" :value="item.value" :key="item.value">{{ item.label }}</Option>
              </Select>
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
                <strong>{{ row.reader_number }}</strong>
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
  </div>
</template>

<script>
export default {
    data(){
        return{
            msg: '',
            search:'',
            showForm: false,//增加信息的表单

            data: [],
            formSearch: {
                content:''
            },
            genderList: [
              {
                value: '男',
                label: '男'
              },{
                value: '女',
                label: '女'
              }
            ],
            formValidate: {
                reader_number: '',
                name: '',
                department: '',
                gender: '',
                telephone: '',
            },
            ruleValidate: {
                reader_number: [
                    { required: true, message: 'The reader_number cannot be empty', trigger: 'blur' }
                ],
                name: [
                    { required: true, message: 'The name cannot be empty', trigger: 'blur' },
                ],
                gender: [
                    { required: true, message: 'The gender cannot be empty', trigger: 'blur' }
                ],
            },
            // columns: [
            //     {
            //         title: '管理员ID',
            //         key: 'reader_number'
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
            editNumber: '',
            editName: '',
            editDepartment: '',
            editGender: '',
            editTelephone: '',
            columns:[
            {
                title: '读者编号',
                key: 'reader_number',
                // id不可更改
                // render: (h, { row, index }) => {
                // return this.renderTableColumn('editNumber', 'reader_number', h, { row, index })
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
                title: '学院',
                key: 'department',
                render: (h, { row, index }) => {
                return this.renderTableColumn('editDepartment', 'department', h, { row, index })
                }
            },
            {
                title: '性别',
                key: 'gender',
                render: (h, { row, index }) => {
                return this.renderTableColumn('editGender', 'gender', h, { row, index })
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
                                    this.data[index].reader_number = this.data[index].reader_number
                                    this.data[index].name = this.editName
                                    this.data[index].department = this.editDepartment
                                    this.data[index].gender = this.editGender
                                    this.data[index].telephone = this.editTelephone
                                    this.editTableIndex = -1
                                    // 在此处配置异步提交
                                    if(editNumber!=''&&editName!=''&&editGender!=''){
                                      fetch("api/bookms/server/reader/edit.php",{
                                        method:"POST",
                                        headers:{
                                            'Accept': 'application/json',
                                            'Content-Type': 'application/json',
                                        },
                                        body:JSON.stringify({
                                            // username:this.data[index],
                                            reader_number: this.data[index].reader_number,
                                            name: this.data[index].name,
                                            department: this.data[index].editDepartment,
                                            gender: this.data[index].editGender,
                                            telephone: this.data[index].telephone,
                                        }),
                                        }).then(res=> { 
                                            return res.json();
                                        }).then(res=>{
                                        if(res[0].code == 1){
                                          console.log(55555)
                                            this.$Message.success(res[0].message);
                                        }else{
                                            this.$Message.error(res[0].message);
                                        }
                                      });
                                    }else{
                                      this.$Message.error("姓名、性别为必填项！");
                                    }
                                    
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
                                            fetch("api/bookms/server/reader/delete.php",{
                                                method:"POST",
                                                headers:{
                                                    'Accept': 'application/json',
                                                    'Content-Type': 'application/json',
                                                },
                                                body:JSON.stringify({
                                                    reader_number:this.data[index].reader_number,
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
    },
    computed:{
      //模糊搜索
      tableData(){
        const search=this.search;
        if(search){
            return this.data.filter(data => {
                return Object.keys(data).some(key => {
                    return String(data[key]).toLowerCase().indexOf(search) > -1
                })
            })
        }
        return this.data;
      }
    },
    methods:{
        showList(){
            fetch('api/bookms/server/reader/showList.php',{
            }).then((res)=>{
                return res.json();
            }).then((res)=>{
                this.data = res[0];
                this.totalNum = res[1];
                // console.log(this.data) // res是最终的结果
            });
            // console.log(1111)//#########有输出 列表不更新
        },
        handleSubmit (name) {
            var _this = this;
            this.$refs[name].validate((valid) => {
                if (valid) {
                    // this.$Message.success('Success!');
                    fetch("api/bookms/server/reader/add.php",{
                        method:"POST",
                        headers:{
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body:JSON.stringify({
                            reader_number:this.formValidate.reader_number,
                            name:this.formValidate.name,
                            department:this.formValidate.department,
                            gender:this.formValidate.gender,
                            telephone:this.formValidate.telephone,
                        }),
                        }).then(res=> { 
                            return res.json();
                        }).then(res=>{
                            if(res[0].code == 1){
                                this.$Message.success(res[0].message);
                                this.$refs[name].resetFields();
                                //###############################
                                // var info = {
                                //     reader_number:this.formValidate.reader_number,
                                //     name:this.formValidate.name,
                                //     department:this.formValidate.department,
                                //     telephone:this.formValidate.telephone,
                                // }
                                // this.data.push(info);

                                // _this.$options.methods.showList();
                            }else{
                                this.$Message.error(res[0].message);
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
        addReader(){
            this.showForm = true;
        },
        // remove (index) {
        //     fetch("api/bookms/server/manager/delete.php",{
        //         method:"POST",
        //         headers:{
        //             'Accept': 'application/json',
        //             'Content-Type': 'application/json',
        //         },
        //         body:JSON.stringify({
        //             reader_number:this.data[index].reader_number,
        //         }),
        //         }).then(res=> { 
        //             return res.json();
        //         }).then(res=>{
        //         if(res[0].code == 1){
        //             this.$Message.success(res[0].message);
        //             this.data.splice(index, 1);
        //         }else{
        //             this.$Message.error(res[0].message);
        //         }
        //     });
        // },
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

