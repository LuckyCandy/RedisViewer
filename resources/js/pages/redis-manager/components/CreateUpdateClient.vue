<template>
    <Modal v-model="isShow" width="360">
        <p slot="header" style="color:#f60;text-align:center">
            <template v-if="isCreate">
                <Icon type="ios-add"/>
                <span>新增服务</span>
            </template>
            <template v-else>
                <Icon type="ios-build-outline"/>
                <span>编辑服务</span>
            </template>
        </p>
        <Form ref="form" :model="formModel">
            <FormItem prop="name">
                <Input type="text" v-model="formModel.name" placeholder="好记的别名">
                    <Icon type="logo-snapchat" slot="prepend"></Icon>
                </Input>
            </FormItem>
            <FormItem prop="address">
                <Input type="text" v-model="formModel.address" placeholder="地址">
                    <Icon type="ios-desktop" slot="prepend"></Icon>
                </Input>
            </FormItem>
            <FormItem :style="{display: 'flex'}">
                <Input type="number" v-model="formModel.port" placeholder="端口号" :style="{width: '48%', float: 'left'}">
                    <Icon type="logo-dribbble" slot="prepend"></Icon>
                </Input>
                <Input type="number" v-model="formModel.db" placeholder="默认库" :style="{width: '48%', float: 'right'}">
                    <Icon type="ios-cube" slot="prepend"></Icon>
                </Input>
            </FormItem>
            <FormItem prop="password">
                <Input type="password" v-model="formModel.password" :placeholder="isCreate ? '密码' : '不修改可不填'">
                    <Icon type="ios-lock-outline" slot="prepend"></Icon>
                </Input>
            </FormItem>
        </Form>
        <div slot="footer">
            <template v-if="isCreate">
                <Button long type="success" :loading="isSaving" @click="handleSubmit">
                    <span v-if="!isSaving">保 存</span>
                    <span v-else>正在测试连接...</span>
                </Button>
            </template>
            <template v-else>
                <Button type="error" @click="handleDelete">
                    <span>删 除</span>
                </Button>
                <Button type="success" :loading="isSaving" @click="handleSubmit">
                    <span v-if="!isSaving">保 存</span>
                    <span v-else>正在测试连接...</span>
                </Button>
            </template>

        </div>
    </Modal>
</template>

<script>
    import {create, update, remove} from '../../../api/redis-client';

    export default {
        name: "CreateUpdateClient",
        data() {
            return {
                isShow: false,
                isCreate: false,
                isSaving: false,
                formModel:{
                    'id': 0, 'name': '', 'address': '', 'password': '', 'port': '', 'db': ''
                }
            }
        },
        methods: {
            openForCreate() {
                this.isCreate = true;
                this.isShow = true;
            },
            openForUpdate(data) {
                this.formModel = data;
                console.log('11111111111', this.formModel);
                this.isCreate = false;
                this.isShow = true;
            },
            handleSubmit() {
                this.isSaving = true;
                if (this.isCreate) {
                    create(this.formModel).then(() => {
                        this.actionDone();
                    }).catch(() => {
                        this.isSaving = false;
                    });
                } else {
                    update(this.formModel.id, this.formModel).then(() => {
                        this.actionDone();
                    }).catch(() => {
                        this.isSaving = false;
                    });
                }
            },
            handleDelete() {
                remove(this.formModel.id).then(() => {
                    this.actionDone();
                }).catch(() => {});
            },
            actionDone() {
                this.formModel = {
                    'address': '', 'password': '', 'port': '', db_no: ''
                };
                this.isSaving = false;
                this.isShow = false;
                this.$emit('refresh');
            }
        }
    }
</script>

<style scoped>

</style>