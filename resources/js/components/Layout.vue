<template>
    <div>
        <div class="lc-header">
            <div class="layout-logo"></div>
            <div class="user-control">
                <Avatar :style="{width: '80px', height: '40px', borderRadius: 0}" :src="user.avatar"/>
            </div>
        </div>

        <div class="lc-content">
            <router-view></router-view>
        </div>

        <!-- 登录弹框 -->
        <Modal v-model="isShowLogin" :closable="false" :mask-closable="false" width="350px" :styles="{top: '152px'}">
            <!--<div class="circle"></div>-->
            <div slot="header">
                <img src="/images/login-title.png" />
            </div>
            <Form ref="loginForm" :model="formData" :rules="ruleValidate">
                <FormItem prop="email" :show-message="false" :style="{marginBottom: '5px'}">
                    <Label :style="{fontSize: '13px', lineHeight: '5px'}"><span style="color:red">*</span> 邮箱</Label>
                    <Input prefix="ios-mail" v-model="formData.email"></Input>
                </FormItem>
                <FormItem prop="password" :show-message="false" :style="{marginBottom: '30px'}">
                    <Label :style="{fontSize: '13px', lineHeight: '5px'}"><span style="color:red">*</span> 密码</Label>
                    <Input prefix="ios-lock" v-model="formData.password" type="password" password ></Input>
                </FormItem>
            </Form>
            <div slot="footer">
                <Button type="primary" size="large" long @click="handleLogin" :loading="isDoLogin">
                    <span v-if="!isDoLogin">登 录!</span>
                    <span v-else>正在登录...</span>
                </Button>
            </div>
        </Modal>
    </div>
</template>

<script>
    import {login} from "../api/login";

    export default {
        name: 'App',
        data() {
            return {
                isShowLogin: false,
                isDoLogin: false,
                isWindowContinueChange: false,
                contentStyle: {
                    height: '',
                    display: 'flex',
                    minHeight: '500px',
                    minWidth: '900px',
                    overflow: 'hidden',
                    background: '#f2f2f2'
                },
                formData: {
                    email: '',
                    password: ''
                },
                ruleValidate: {
                    password: [
                        { required: true, message: '', trigger: 'blur' }
                    ],
                    email: [
                        { required: true, message: '', trigger: 'blur' },
                    ]
                },
                user: {
                    name: '',
                    avatar: '/images/login.png'
                }
            }
        },
        beforeCreate() {
            EBUS.$on('EVENT-USER-UNLOGIN', () => {
                this.user.avatar = '/images/un-login.png';
                this.isShowLogin = true;
            });
        },
        beforeDestroy() {
            EBUS.$off('EVENT-USER-UNLOGIN');
        },
        methods: {
            handleLogin() {
                this.$refs.loginForm.validate((valid) => {
                    if (valid) {
                        this.isDoLogin = true;
                        login(this.formData).then(response => {
                            window.localStorage.setItem('gaea.token', response.data.token);
                            this.user.name = response.data.name;
                            this.user.avatar = '/images/login.png';
                            this.isDoLogin = false;
                            this.isShowLogin = false;
                            this.handleReset();
                            this.$Message['success']({
                                background:true,
                                content: '登录成功'
                            });
                            /* 登录成功，通知组件刷新 */
                            EBUS.$emit('EVENT-USER-LOGIN', response.data);
                        }).catch(error => {
                            this.isDoLogin = false;
                        });
                    } else {
                        this.$Message.error('填写的信息有误，请检查');
                    }
                });
            },
            handleReset() {
                this.formData = {
                    email: '', password: ''
                }
            }
        }
    }
</script>

<style scoped>
    @import url("/css/app.css");
    .layout-logo{
        width: 150px;
        height: 40px;
        background: url(/images/nav-logo.png) left center no-repeat;
        position: relative;
        display: inline-block;
        padding: 8px 10px;
        top: 15px;
        left: 10px;
    }
    .user-control{
        position: relative;
        top: 15px;
        float: right;
        right: 8px;
    }
    .lc-header {
        width: 100%;
        height: 64px;
        background: #0878ec;
        border-bottom: 1px solid;
        position: absolute;
    }
    .lc-content {
        display: flex;
        position: absolute;
        top: 64px;
        bottom: 0;
        overflow: hidden;
        background: rgb(242, 242, 242);
        width: 100%;
    }
    .lc-footer {
        height: 30px;
        text-align: center;
        background: white;
    }
</style>