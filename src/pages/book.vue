<template>
  <div id="main">
        <div class="topButton">
            <Button type="primary" @click="addReader" class="addBtn">新增</Button>
            <!-- <Button type="error">批量删除</Button> -->

            <Form class="searchBox">
                <Input type="text" v-model="search" placeholder="Search..." icon="ios-search" />
            </Form>
        </div>

        <!-- 新增 -->
        <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" 
                v-if="showForm" :label-width="100"  inline>
            <FormItem label="图书编号" prop="book_number">
                <Input v-model="formValidate.book_number" placeholder="Enter book_number" />
            </FormItem>
            <FormItem label="类别" prop="category">
                <Input v-model="formValidate.category" placeholder="Enter category" />
            </FormItem>
            <FormItem label="书名" prop="book_name">
                <Input v-model="formValidate.book_name" placeholder="Enter book_name" />
            </FormItem>
            <FormItem label="出版社" prop="publisher">
                <Input v-model="formValidate.publisher" placeholder="Enter publisher" />
            </FormItem>
            <FormItem label="作者" prop="author">
                <Input v-model="formValidate.author" placeholder="Enter author" />
            </FormItem>
            <FormItem label="价格" prop="price">
                <Input v-model="formValidate.price" @on-change:="judge()" placeholder="Enter price" />
            </FormItem>
            <FormItem label="总藏书量" required prop="book_total">
                <Input v-model="formValidate.book_total" placeholder="Enter book_total" />
            </FormItem>
            <FormItem label="库存" prop="inventory">
                <Input v-model="formValidate.inventory" :disabled="true" placeholder="根据总量计算得出" />
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
            <FormItem label="图书编号" prop="book_number">
                <Input v-model="formEdit.book_number" :disabled="true" placeholder="Enter book_number" />
            </FormItem>
            <FormItem label="类别" prop="category">
                <Input v-model="formEdit.category" placeholder="Enter category" />
            </FormItem>
            <FormItem label="书名" prop="book_name">
                <Input v-model="formEdit.book_name" placeholder="Enter book_name" />
            </FormItem>
            <FormItem label="出版社" prop="publisher">
                <Input v-model="formEdit.publisher" placeholder="Enter publisher" />
            </FormItem>
            <FormItem label="作者" prop="author">
                <Input v-model="formEdit.author" placeholder="Enter author" />
            </FormItem>
            <FormItem label="价格" prop="price">
                <Input v-model="formEdit.price" placeholder="Enter price" />
            </FormItem>
            <FormItem label="总藏书量" required prop="book_total">
                <Input v-model="formEdit.book_total" placeholder="Enter book_total" />
            </FormItem>
            <FormItem label="库存" prop="inventory">
                <Input v-model="formEdit.inventory" :disabled="true" placeholder="根据总量计算得出" />
            </FormItem>
            <FormItem>
                <Button type="primary" @click="handleEditSubmit('formEdit')">确定</Button>
                <Button @click="handleReset('formEdit')" style="margin-left: 8px">重置</Button>
                <Button @click="handleEditCancel()" style="margin-left: 8px">取消</Button>
            </FormItem>
        </Form>

        <!-- 表格 -->
        <Table border :columns="columns" :data="tableData">
           <template slot-scope="{ row }" slot="book_name"> 
                <strong>{{ row.book_number }}</strong>
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
        const validatePrice = (rule, value, callback) => {
            if(value==''){
                callback();
            }else if (value<=0) {
                callback(new Error('Must more than 0!'));
            }else{
                callback();
            }
        };
        const validateBookTotal = (rule, value, callback) => {
            if (value==='') {
                callback(new Error('The book_total cannot be empty！'));
            }else if(value<=0){
                callback(new Error('Must more than 0!'));
            }else{
                callback();
            }
        };
        return{
            msg: '',
            search:'',
            showForm: false,//增加信息的表单
            showEditForm:false,//编辑信息的表单
            data: [],
            formEdit: {
                book_number: '',
                category:'',
                book_name: '',
                publisher: '',
                author: '',
                price: '',
                book_total:'',
                inventory:'',
            },
            formValidate: {
                book_number: '',
                category:'',
                book_name: '',
                publisher: '',
                author: '',
                price: '',
                book_total:'',
                inventory:'',
            },
            ruleValidate: {
                book_number: [
                    { required: true, message: 'The book_number cannot be empty', trigger: 'blur' }
                ],
                book_name: [
                    { required: true, message: 'The book_name cannot be empty', trigger: 'blur' },
                ],
                publisher: [
                    { required: true, message: 'The publisher cannot be empty', trigger: 'blur' }
                ],
                price: [
                    {validator: validatePrice, trigger: 'blur'},
                    // { type :'number', message:　'请输入数字', trigger: 'blur', transform(value) {return Number(value);}}
                ],
                book_total: [
                    {validator: validateBookTotal,trigger: 'blur'},
                    // { required: true, message: 'The book_total cannot be empty', trigger: 'blur' },
                    // { type: 'number', message: '请输入数字', trigger: 'blur', transform(value) {return Number(value);}}
                ],
                // inventory: [
                //     { required: true, message: 'The inventory cannot be empty', trigger: 'blur' },
                //     { type: 'number', message: '请输入数字', trigger: 'blur', transform(value) {return Number(value);}}
                // ],
            },
            columns: [
                {
                    title: '图书编号',
                    key: 'book_number'
                },{
                    title: '类别',
                    key: 'category'
                },{
                    title: '书名',
                    key: 'book_name'
                },{
                    title: '出版社',
                    key: 'publisher'
                },{
                    title: '作者',
                    key: 'author'
                },{
                    title: '价格',
                    key: 'price'
                },{
                    title: '总藏书量',
                    key: 'book_total'
                },{
                    title: '库存',
                    key: 'inventory'
                },{
                    title: '操作',
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
            fetch('api/bookms/server/book/showList.php',{
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
        },
        handleSubmit (name) {
            this.$refs[name].validate((valid) => {
                if (valid) {
                  fetch("api/bookms/server/book/add.php",{
                      method:"POST",
                      headers:{
                          'Accept': 'application/json',
                          'Content-Type': 'application/json',
                      },
                      body:JSON.stringify({
                          Authorization:localStorage.getItem('Authorization'),
                          book_number:this.formValidate.book_number,
                          category:this.formValidate.category,
                          book_name:this.formValidate.book_name,
                          author:this.formValidate.author,
                          publisher:this.formValidate.publisher,
                          price:this.formValidate.price,
                          book_total:this.formValidate.book_total,
                          inventory:this.formValidate.book_total,
                      }),
                    }).then(res=> { 
                        return res.json();
                    }).then(res=>{
                        if(res[1].code==1){
                            if(res[0].code == 1){
                                this.$Message.success(res[0].message);
                                this.$refs[name].resetFields();
                                this.showList();                          
                            }else{
                                this.$Message.error(res[0].message);
                            }
                        }else{
                            this.$Message.error(res[1].message);
                            this.$router.push('./index');
                        }
                    });
                } else {
                    this.$Message.error('请按要求完成!');
                }
            })
        },
        handleEditSubmit(){
            fetch("api/bookms/server/book/edit.php",{
                method:"POST",
                headers:{
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body:JSON.stringify({
                    Authorization:localStorage.getItem('Authorization'),
                    book_number:this.formEdit.book_number,
                    category:this.formEdit.category,
                    book_name:this.formEdit.book_name,
                    author:this.formEdit.author,
                    publisher:this.formEdit.publisher,
                    price:this.formEdit.price,
                    book_total:this.formEdit.book_total,
                    // inventory:this.formEdit.book_total,//#########计算
                }),
            }).then(res=> { 
                return res.json();
            }).then(res=>{
                if(res[1].code==1){
                    if(res[0].code == 1){
                        this.showList();
                        this.$Message.success(res[0].message);
                        this.data.splice(index, 1);
                    }else{
                        this.$Message.error(res[0].message);
                    }
                }else{
                    this.$Message.error(res[1].message);
                    this.$router.push('./index');
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
            //     book_number:'',
            //     category:'',
            //     book_name: '',
            //     author: '',
            //     publisher: '',
            //     price: '',
            //     book_total: '',
            //     inventory: '',
            // }
        },
        addReader(){
            this.showForm = true;
        },
        edit(index){
            this.showEditForm = true;//显示表单
            this.formEdit.book_number = this.data[index].book_number;
            this.formEdit.category = this.data[index].category;
            this.formEdit.book_name = this.data[index].book_name;
            this.formEdit.author = this.data[index].author;
            this.formEdit.publisher = this.data[index].publisher;
            this.formEdit.price = this.data[index].price;
            this.formEdit.book_total = this.data[index].book_total;
            this.formEdit.inventory = "系统将根据计算修改";
        },
        remove (index) {
            fetch("api/bookms/server/book/delete.php",{
                method:"POST",
                headers:{
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body:JSON.stringify({
                    Authorization:localStorage.getItem('Authorization'),
                    book_number:this.data[index].book_number,
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

