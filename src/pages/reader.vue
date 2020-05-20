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

        <!-- 新增 -->
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

        <!-- 编辑 -->
        <Form ref="formEdit" :model="formEdit" :rules="ruleValidate" :label-width="100"  
                v-if="showEditForm" inline>
            <FormItem label="读者编号" prop="reader_number">
                <Input v-model="formEdit.reader_number" :disabled="true" placeholder="Enter reader_number" />
            </FormItem>
            <FormItem label="姓名" prop="name">
                <Input v-model="formEdit.name" placeholder="Enter name" />
            </FormItem>
            <FormItem label="所属学院" prop="department">
                <Input v-model="formEdit.department" placeholder="Enter department" />
            </FormItem>
            <FormItem label="性别" prop="gender">
                <!-- <Input v-model="formEdit.telephone" placeholder="Enter gender" /> -->
                <Select v-model="formEdit.gender" style="width:200px">
                  <Option v-for="item in genderList" :value="item.value" :key="item.value">{{ item.label }}</Option>
              </Select>
            </FormItem>
            <FormItem label="联系方式" prop="telephone">
                <Input v-model="formEdit.telephone" placeholder="Enter telephone" />
            </FormItem>
            <FormItem>
                <Button type="primary" @click="handleEditSubmit('formEdit')">确定</Button>
                <Button @click="handleReset('formEdit')" style="margin-left: 8px">重置</Button>
                <Button @click="handleEditCancel()" style="margin-left: 8px">取消</Button>
            </FormItem>
        </Form>

        <!-- 表格 -->
        <Table border :columns="columns" :data="tableData">
           <template slot-scope="{ row }" slot="name"> 
                <strong>{{ row.reader_number }}</strong>
            </template>
            <template slot-scope="{ row, index }" slot="action">
                <!-- <Button type="primary" size="small" style="margin-right: 5px" @click="show(index)">查看</Button> -->
                <Button type="primary" size="small" @click="edit(index)">编辑</Button>
                <Button type="error" size="small" @click="remove(index)">删除</Button>
            </template>
        </Table>
        <!-- <Table
            :data="tableData"
            :columns="columns"
            border
        ></Table> -->
  </div>
</template>

<script>
export default {
    data(){
        return{
            msg: '',
            search:'',
            showForm: false,//增加信息的表单
            showEditForm:false,//编辑信息的表单
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
            formEdit: {
                reader_number: '',
                name: '',
                department: '',
                gender: '',
                telephone: '',
            },
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
            columns: [
                {
                    title: '读者编号',
                    key: 'reader_number',
                },{
                    title: '姓名',
                    key: 'name'
                },{
                    title: '学院',
                    key: 'department'
                },{
                    title: '性别',
                    key: 'gender'
                },{
                    title: '联系方式',
                    key: 'telephone'
                },{
                    title: 'Action',
                    slot: 'action',
                    width: 150,
                    align: 'center'
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
                console.log(this.data) // res是最终的结果
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

                                _this.$options.methods.showList();
                            }else{
                                this.$Message.error(res[0].message);
                            }
                        });
                } else {
                    this.$Message.error('请完成必填项!');
                }
            })
        },
        handleEditSubmit(){
            fetch("api/bookms/server/reader/edit.php",{
                method:"POST",
                headers:{
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body:JSON.stringify({
                    reader_number:this.formEdit.reader_number,
                    name:this.formEdit.name,
                    department:this.formEdit.department,
                    gender:this.formEdit.gender,
                    telephone:this.formEdit.telephone,
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
        },
        handleCancel(){
            this.showForm = false;
        },
        handleEditCancel(){
            this.showEditForm = false;
        },
        handleReset (name) {
            this.$refs[name].resetFields();
        },
        handleEditReset (name) {//#############重置表单未完成
            console.log("重置表单未完成")
            // this.formEdit={
            //     reader_number:'',
            //     name: '',
            //     department: '',
            //     gender: '',
            //     telephone: '',
            // }
        },
        addReader(){
            this.showForm = true;
        },
        edit(index){
            this.showEditForm = true;
            // console.log("未完成");
            this.formEdit.reader_number = this.data[index].reader_number;
            this.formEdit.name = this.data[index].name;
            this.formEdit.department = this.data[index].department;
            this.formEdit.gender = this.data[index].gender;
            this.formEdit.telephone = this.data[index].telephone;
        },
        remove (index) {
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

