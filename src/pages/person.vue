<template>
  <div id="main">
    <Tabs value="name1">
        <TabPane label="管理员" name="manager" @click="handleManager">
            <router-view/>
        </TabPane>
        <TabPane label="读者" name="reader"  @click="handleReader">
            
        </TabPane>
        <!-- <router-view/> -->
    </Tabs>
  </div>
</template>

<script>
export default {
    methods:{
        handleManager(){
            this.$router.push('./manager');
            console.log(1)
        },
        handleReader(){
            // this.$router.push('./person/reader');
        }
    }
  
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  /* -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale; */
  /* text-align: center; */
  /* color: #198290; */
  /* margin-top: 60px; */
}
</style>
