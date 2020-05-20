<template>
    <Form ref="formInline" :model="formInline" :rules="ruleInline" inline>
        <FormItem prop="user">
            <Input type="text" v-model="formInline.email" placeholder="Your Email">
                <Icon type="ios-person-outline" slot="prepend"></Icon>
            </Input>
        </FormItem>
        <FormItem prop="password">
            <Input type="password" v-model="formInline.password" placeholder="Password">
                <Icon type="ios-lock-outline" slot="prepend"></Icon>
            </Input>
        </FormItem>
        <FormItem>
            <Button type="primary" @click="handleSubmit('formInline')">Signin</Button>
        </FormItem>
    </Form>
</template>
<script>
    import {login} from "../../api/login";

    export default {
        data () {
            return {
                formInline: {
                    email: '',
                    password: ''
                },
                ruleInline: {
                    email: [
                        { required: true, message: 'Please fill in the email', trigger: 'blur' }
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
                        login(this.formInline).then(response => {
                            console.log(response)
                        }).catch(error => {
                            console.log(error)
                        })
                    } else {
                        this.$Message.error('Fail!');
                    }
                });
            }
        }
    }
</script>
