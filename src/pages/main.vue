<template>
<div class="layout">
        <Layout>
            <Header>
                <Menu mode="horizontal" theme="dark" active-name="1">
                    <div class="layout-logo" style="color:white">
                        <Icon type="md-paper" />
                        图书管理系统
                    </div>
                    <div class="layout-nav">
                        <Icon type="ios-power" style="color:white" @click="logout"/>
                    </div>
                </Menu>
            </Header>

            <Layout>
                <Sider breakpoint="md" collapsible :collapsed-width="78" v-model="isCollapsed">
                    <Menu :active-name="activeName" theme="dark" width="auto" :class="menuitemClasses">
                        <MenuItem name="manager" @click.native="handleManager">
                                <Icon type="ios-person"></Icon>
                                <span>管理员</span>
                        </MenuItem>
                        <MenuItem name="reader" @click.native="handleReader">
                                <Icon type="ios-people"></Icon>
                                <span>读者信息</span>
                        </MenuItem>
                        <MenuItem name="book" @click.native="handleBook">
                                <Icon type="ios-book"></Icon>
                                <span>图书管理</span>
                        </MenuItem>
                        <MenuItem name="option" @click.native="handleOption" >
                                <Icon type="ios-bookmarks"></Icon>
                                <span>借还书操作</span>
                        </MenuItem>
                    </Menu>
                    <div slot="trigger"></div>
                </Sider>
                <Layout :style="{padding: '0 24px 24px'}">
                    <br>
                    <Content :style="{padding: '24px', minHeight: '280px', background: '#fff'}">
                        <router-view />
                    </Content>
                </Layout>
            </Layout>
        </Layout>
    </div>
</template>

<script>
import { mapMutations } from 'vuex';
export default {
    data () {
        return {
            isCollapsed: false,
            activeName:'manager'
        };
    },
    computed: {
        menuitemClasses: function () {
            return [
                'menu-item',
                this.isCollapsed ? 'collapsed-menu' : ''
            ]
        }
    },
    mounted(){
            // console.log(this.$route.path.slice(1))
            // this.$nextTick(()=>{
                this.activeName = this.$route.path.slice(1)
                // this.$refs.activeName.updateActiveName()
            // })
        },
    methods:{
        ...mapMutations(['changeLogin']),
        logout(){
            this.changeLogin({ Authorization: '' });
            this.$router.push('./index')
        },
        handleManager(){
            this.$router.push('./manager');
        },
        handleReader(){
            this.$router.push('./reader');
        },
        handleBook(){
            this.$router.push('./book');
        },
        handleOption(){
            this.$router.push('./option');
        },
    }
    
}
</script>

<style scoped>
.layout{
    border: 1px solid #d7dde4;
    background: #f5f7f9;
    position: relative;
    border-radius: 4px;
    overflow: hidden;
}
.layout-logo{
    font-size: 22px;
    width: 200px;
    height: 30px;
    float: left;
    position: relative;
}
.layout-nav{
    width: 10px;
    margin: 0 auto;
    margin-right: 10px;
}
.ivu-layout-header{
    padding: 0 25px;
}
.layout-header-bar{
    background: #fff;
    box-shadow: 0 1px 1px rgba(0,0,0,.1);
}
.menu-item span{
    display: inline-block;
    overflow: hidden;
    width: 69px;
    text-overflow: ellipsis;
    white-space: nowrap;
    vertical-align: bottom;
    transition: width .2s ease .2s;
}
.menu-item i{
    transform: translateX(0px);
    transition: font-size .2s ease, transform .2s ease;
    vertical-align: middle;
    font-size: 16px;
}
.collapsed-menu span{
    width: 0px;
    transition: width .2s ease;
}
.collapsed-menu i{
    transform: translateX(5px);
    transition: font-size .2s ease .2s, transform .2s ease .2s;
    vertical-align: middle;
    font-size: 22px;
}
</style>
