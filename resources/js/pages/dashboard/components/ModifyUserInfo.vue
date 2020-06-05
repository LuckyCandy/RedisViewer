<template>
    <Modal ref="modal" v-model="isShow" width="360" @on-ok="handleSubmit" @on-cancel="handleCancel" :mask-closable="false" :loading="true">
        <p slot="header" style="color:#f60;text-align:center">
            <Icon type="ios-information-circle"></Icon>
            <span>修改个人信息</span>
        </p>
        <Row :style="{marginBottom: '15px'}">
            <Col span="4" :style="{lineHeight: '30px', minWidth: '48px'}">
                <span style="color:red"><b>* </b></span>昵称:
            </Col>
            <Col span="20" :style="nameControl">
                <Input type="text" v-model="formModel.name">
                    <Icon type="ios-person-outline" slot="prepend"></Icon>
                </Input>
            </Col>
        </Row>
        <Divider :style="{color:'rgb(246, 101, 4)', fontSize: '12px'}">不修改密码无需填写</Divider>
        <Row :style="{marginBottom: '15px'}">
            <Col span="4" :style="{lineHeight: '30px', minWidth: '48px'}">
                原密码:
            </Col>
            <Col span="20" :style="passwdControl">
                <Input type="text" v-model="formModel.password">
                    <Icon type="ios-person-outline" slot="prepend"></Icon>
                </Input>
            </Col>
        </Row>
        <Row :style="{marginBottom: '15px'}">
            <Col span="4" :style="{lineHeight: '30px', minWidth: '48px'}">
                新密码:
            </Col>
            <Col span="20" :style="newPasswdControl">
                <Input type="text" v-model="formModel.new_password">
                    <Icon type="ios-person-outline" slot="prepend"></Icon>
                </Input>
            </Col>
        </Row>
    </Modal>
</template>

<script>
    import {update} from '../../../api/user';

    export default {
        name: "ModifyUserInfo",
        data() {
            const errorStyle = {
                border: '1px dashed red',
                borderRadius: '5px'
            };
            const normalStyle = {
                border: 'none'
            };
            return {
                nameControl: normalStyle,
                passwdControl: normalStyle,
                newPasswdControl: normalStyle,
                errorStyle: errorStyle,
                isShow: false,
                formModel: {
                    name: '',
                    password: '',
                    new_password: ''
                }
            }
        },
        methods : {
            handleCancel() {
                this.isShow = false;
            },
            handleSubmit() {
                let body = {};
                if (this.formModel.name == '') {
                    this.$Message.error('昵称不能为空哦~');
                    this.nameControl = this.errorStyle;
                    this.$refs.modal.buttonLoading = false;
                    return false;
                }
                body.name = this.formModel.name;

                if (this.formModel.password != '') {
                    if (this.formModel.password.length < 6) {
                        this.$Message.error('密码最短6位，字符数字混合更安全哦~');
                        this.nameControl = this.errorStyle;
                        this.$refs.modal.buttonLoading = false;
                        return false;
                    }
                    if (this.formModel.new_password == '' || this.formModel.new_password.length < 6) {
                        this.$Message.error('密码最短6位，字符数字混合更安全哦~');
                        this.nameControl = this.errorStyle;
                        this.$refs.modal.buttonLoading = false;
                        return false;
                    }
                    body.password = this.formModel.password;
                    body.new_password = this.formModel.new_password;
                }

                update(body).then(() => {
                    this.isShow = false;
                    this.$emit('refreshUserName', this.formModel.name);
                    this.$Message.success('更新成功~');
                }).catch((error) => {});
                this.$refs.modal.buttonLoading = false;

            },
            open(data) {
                this.formModel.name = data.name;
                this.isShow = true;

            }
        }
    }
</script>

<style scoped>

</style>