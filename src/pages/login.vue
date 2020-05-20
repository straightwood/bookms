<template>
  <div id="main">
    <div id="box">
      <Card id="loginCard">
          <div style="text-align:center">
            <h3>欢迎进入</h3>
            <h1>图书管理系统</h1><br><br><br>
          </div>
          <Form ref="formInline" :model="formInline" :rules="ruleInline" style="width: 200px">
              <FormItem prop="user">
                  <Input type="text" v-model="formInline.username" placeholder="Uername eg:admin">
                      <Icon type="ios-person-outline" slot="prepend" />
                  </Input>
              </FormItem>
              <FormItem prop="password">
                  <Input type="password" v-model="formInline.password" placeholder="Password eg:123123">
                      <Icon type="ios-lock-outline" slot="prepend" />
                  </Input>
              </FormItem>
              <FormItem>
                  <Button type="primary" style="width:200px" @click="handleSubmit('formInline')">登录</Button>
              </FormItem>
          </Form>
      </Card>
    </div>
    
  </div>
</template>

<script>
export default {
        data () {
            return {
                formInline: {
                    username: '',
                    password: ''
                },
                ruleInline: {
                    username: [
                        { required: true, message: 'Please fill in the user name', trigger: 'blur' }
                    ],
                    password: [
                        { required: true, message: 'Please fill in the password.', trigger: 'blur' },
                        { type: 'string', min: 6, message: 'The password length cannot be less than 6 bits', trigger: 'blur' }
                    ]
                }
            }
        },
        methods: {
            handleSubmit(name) {
                this.$refs[name].validate((valid) => {
                    if (valid) {
                      fetch("api/bookms/server/login.php",{
                        method:"POST",
                        headers:{
                          'Accept': 'application/json',
                          'Content-Type': 'application/json',
                        },
                        body:JSON.stringify({
                          username:this.formInline.username,
                          password:this.formInline.password,
                        }),
                      }).then(res=> { 
                        return res.json()
                      }).then(res=>{
                        if(res[0].code == 1){
                          this.$Message.success(res[0].message);
                          this.$router.push('/main')
                        }else{
                          this.$Message.error(res[0].message);
                        }
                      });
                    } else {
                        this.$Message.error();
                    }
                })
            },
        }
    }
</script>

<style scoped>
#main {
  background: url('../assets/BG.png') no-repeat;
  /* background-size: cover; */
  min-height: 1080px;
  text-align: center;
  /* background-position: 50% 0px; */
  /* font-family: 'Avenir', Helvetica, Arial, sans-serif;*/
}
#box{
  position: relative;
}
#loginCard{
  background-color: rgba(255, 255, 255, 0.505);
  position: absolute;
  margin: 15% 40%;
  width:320px;
  padding: 40px;
  /* margin: 0 auto; */
}
</style>
