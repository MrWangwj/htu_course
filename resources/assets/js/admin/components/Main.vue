<template>
    <div>
        <h1>欢迎使用课表管理系统</h1>
    </div>
</template>
<script>
    import { XInput, Group } from 'vux'

    export default {
        components: {
            XInput,
            Group
        },
        data () {
            return {
                password: '123465',
                password2: '',
                enterText: '',
                valid1: false,
                valid2: false,
                iconType: '',
                be2333: function (value) {
                    return {
                        valid: value === '2333',
                        msg: 'Must be 2333'
                    }
                },
                style: '',
                disabledValue: 'hello',
                debounceValue: '',
                maxValue: '',
                maskValue: '13545678910',
                maskValue2: ''
            }
        },
        methods: {

        },

    }
</script>