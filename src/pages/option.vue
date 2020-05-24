<template>
  <div id="main">
    <Tabs value="name1">
      <TabPane label="借书" name="name1">
        <br>
        <Form ref="formBorrow" :model="formBorrow" :rules="ruleValidate" :label-width="100"  inline>
            <FormItem label="读者编号" prop="reader_number">
                <Input v-model="formBorrow.reader_number" placeholder="Enter reader_number" />
            </FormItem>
            <FormItem label="书号" prop="book_number">
                <Input v-model="formBorrow.book_number" placeholder="Enter book_number" />
            </FormItem>
           <FormItem label="日期" prop="date"> <!-- 注意返回数据的方法 -->
                <DatePicker type="datetime" @on-change="time1" format="yyyy-MM-dd HH:mm:ss" placeholder="Select date and time"></DatePicker>
                <!-- <Input v-model="formBorrow.date" placeholder="Enter date" /> -->
            </FormItem>
            <FormItem>
                <Button type="primary" @click="borrowSubmit('formBorrow')">确定</Button>
                <Button @click="handleReset('formBorrow')" style="margin-left: 8px">重置</Button>
            </FormItem>
        </Form>
      </TabPane>
      <TabPane label="还书" name="name2" style="height:500px">
        <br>
        <Form ref="formReturn" :model="formReturn" :rules="ruleValidate" :label-width="100"  inline>
            <FormItem label="读者编号" prop="reader_number">
                <Input v-model="formReturn.reader_number" placeholder="Enter reader_number" />
            </FormItem>
            <FormItem label="书号" prop="book_number">
                <Input v-model="formReturn.book_number" placeholder="Enter book_number" />
            </FormItem>
            <FormItem label="日期" prop="date">
                <DatePicker type="datetime" @on-change="time2" format="yyyy-MM-dd HH:mm:ss" placeholder="Select date and time"></DatePicker>
            </FormItem>
            <FormItem>
                <Button type="primary" @click="returnSubmit('formReturn')">确定</Button>
                <Button @click="handleReset('formReturn')" style="margin-left: 8px">重置</Button>
            </FormItem>
        </Form>
      </TabPane>
    </Tabs>
  </div>
</template>

<script>
export default {
  data(){
    return{
      // showBorrowForm: false,
      // showReturnForm:false,
      formBorrow: {
          reader_number: '',
          book_number: '',
          date:'',
      },
      formReturn: {
          reader_number: '',
          book_number: '',
          date:'',
      },
      ruleValidate: {
          reader_number: [
              { required: true, message: 'The reader_number cannot be empty', trigger: 'blur' }
          ],
          book_number: [
              { required: true, message: 'The name cannot be empty', trigger: 'blur' },
          ],
          date: [
              { required: true, message: '请选择日期', trigger: 'blur'}
          ],//日期校验问题
      },
    }
  },
  methods:{
    time1(e){
      this.formBorrow.date = e;
      console.log(this.formBorrow.date);
    },
    time2(e){
      this.formReturn.date = e;
      console.log(this.formReturn);
    },
    borrowSubmit(name){
      this.$refs[name].validate((valid) => {
        if (valid) {
          fetch("api/bookms/server/option/borrow.php",{
              method:"POST",
              headers:{
                  'Accept': 'application/json',
                  'Content-Type': 'application/json',
              },
              body:JSON.stringify({
                  Authorization:localStorage.getItem('Authorization'),//token
                  reader_number:this.formBorrow.reader_number,
                  book_number:this.formBorrow.book_number,
                  date:this.formBorrow.date,
              }),
          }).then(res=> { 
              return res.json();
          }).then(res=>{
            console.log(res)
              if(res[1].code==1){
                  if(res[0].code == 1){
                      this.$Message.success(res[0].message);
                      this.$refs[name].resetFields();                           
                  }else{
                      this.$Message.error(res[0].message);
                  }
              }else{
                  this.$Message.error(res[1].message);
                  this.$router.push('./index');
              }
          });
      } else {
          this.$Message.error('请完成必填项!');
        }
      })
    },
    returnSubmit(name){
      this.$refs[name].validate((valid) => {
        if (valid) {
          fetch("api/bookms/server/option/return.php",{
              method:"POST",
              headers:{
                  'Accept': 'application/json',
                  'Content-Type': 'application/json',
              },
              body:JSON.stringify({
                  Authorization:localStorage.getItem('Authorization'),//token
                  reader_number:this.formReturn.reader_number,
                  book_number:this.formReturn.book_number,
                  date:this.formReturn.date,
              }),
          }).then(res=> { 
              return res.json();
          }).then(res=>{
            console.log(res)
              if(res[1].code==1){
                  if(res[0].code == 1){
                      this.$Message.success(res[0].message);
                      this.$refs[name].resetFields();                           
                  }else{
                      this.$Message.error(res[0].message);
                  }
              }else{
                  this.$Message.error(res[1].message);
                  this.$router.push('./index');
              }
          });
      } else {
          this.$Message.error('请完成必填项!');
        }
      })
    },
    handleReset (name) {
        this.$refs[name].resetFields();
    },
    
  }
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
}
</style>
