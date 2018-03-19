<template>
    <div>
        <div>
            <el-row>
                <el-col :span="24">
                    <div class="grid-content bg-purple">
                        <el-button type="primary" size="mini" @click="addUserBut">添加</el-button>
                        <!--<el-button type="primary" size="mini">导入</el-button>-->
                        <!--<el-select v-model="value" placeholder="请选择" size="mini" class="">-->
                            <!--<el-option-->
                                    <!--v-for="item in options"-->
                                    <!--:key="item.value"-->
                                    <!--:label="item.label"-->
                                    <!--:value="item.value">-->
                            <!--</el-option>-->
                        <!--</el-select>-->
                    </div>
                </el-col>
            </el-row>
            <div style="margin-top: 15px;">
                <el-table
                        :data="users"
                        border
                        style="width: 100%">
                    <el-table-column
                            prop="account"
                            label="学号">
                    </el-table-column>
                    <el-table-column
                            prop="name"
                            label="姓名">
                    </el-table-column>

                    <el-table-column
                            prop="sex"
                            label="性别">
                    </el-table-column>

                    <el-table-column
                            label="专业">
                        <template slot-scope="scope">
                            {{ scope.row.majors.name }}
                        </template>
                    </el-table-column>

                    <el-table-column label="操作">
                        <template slot-scope="scope">
                            <el-button-group>
                                <el-button
                                        size="mini"
                                        type="primary"
                                        icon="el-icon-zoom-in"
                                        @click="seeCourseBut(scope.row.id)">
                                </el-button>

                                <el-button
                                        size="mini"
                                        type="primary"
                                        icon="el-icon-edit"
                                        @click="editUserBut(scope.$index, scope.row)">

                                </el-button>

                                <el-button
                                        size="mini"
                                        type="danger"
                                        icon="el-icon-delete"
                                        @click="deleteUserBut(scope.row.id)">
                                </el-button>
                            </el-button-group>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </div>


        <el-dialog :title="UserDialogType === 0 ? '添加用户':'编辑用户'" :visible.sync="UserDialog">
            <el-form :model="user">
                <el-form-item label="学号" label-width="100px">
                    <el-input v-model.number="user.account" style="width: 194px;">
                    </el-input>
                </el-form-item>

                <el-form-item label="姓名" label-width="100px">
                    <el-input v-model="user.name" style="width: 194px;">
                    </el-input>
                </el-form-item>
                <el-form-item label="性别" label-width="100px">
                    <el-radio v-model="user.sex" label="男">男</el-radio>
                    <el-radio v-model="user.sex" label="女">女</el-radio>
                </el-form-item>
                <el-form-item label="专业" label-width="100px">
                    <el-select v-model="user.major_id" placeholder="请选择">
                        <el-option
                                v-for="major in data.majors"
                                :key="major.id"
                                :label="major.name"
                                :value="major.id">
                        </el-option>
                    </el-select>
                </el-form-item>

            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="UserDialog = false">取 消</el-button>
                <el-button type="primary" @click="save()">保存</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                UserDialog: false,
                UserDialogType: 0,
                user: {
                    id: '',
                    account: '',
                    name: '',
                    sex: '',
                    major_id: '',
                },
                data: {
                    majors: [],
                },
                users: []
            }
        },
        methods: {
            getType(){
                axios.get('/admin/user/type').then(res => {
                    this.data = res.data;
                });
            },
            getData(){
                axios.get('/admin/user/data').then(res => {
                    this.users = res.data;
                });
            },

            addUserBut(){
                this.user ={
                    id: '',
                    name: '',
                    sex: '',
                    major_id: '',
                };
                this.UserDialogType = 0;
                this.UserDialog = true;
            },
            editUserBut(index, row){
                this.user ={
                    id: row.id,
                    account: row.account,
                    name: row.name,
                    sex: row.sex,
                    major_id: row.major_id,
                };

                this.UserDialogType = 1;
                this.UserDialog = true;
            },
            addUserFun(){
                axios.post('/admin/user/add',this.user).then(res => {
                    let data = res.data;
                    if(data.code === 1){
                        this.$message({
                            message: data.msg,
                            type: 'success'
                        });
                        this.UserDialog = false;
                        this.getData();
                    }else{
                        this.$message.error(data.msg);
                    }
                    // console.log(data);
                });
            },
            editUserFun(){
                axios.post('/admin/user/edit',this.user).then(res => {
                    let data = res.data;
                    if(data.code === 1){
                        this.$message({
                            message: data.msg,
                            type: 'success'
                        });
                        this.UserDialog = false;
                        this.getData();
                    }else{
                        this.$message.error(data.msg);
                    }
                    // console.log(data);
                });
            },
            save(){
                if(this.UserDialogType === 0)
                    this.addUserFun();
                else
                    this.editUserFun();

            },

            deleteUserBut(id){
                this.$confirm('确认要删除吗？')
                    .then(_ => {
                        axios.post('/admin/user/delete',{
                            id: id,
                        }).then(res => {
                            let data = res.data;
                            if(data.code === 1){
                                this.$message({
                                    message: data.msg,
                                    type: 'success'
                                });
                                this.UserDialog = false;
                                this.getData();
                            }else{
                                this.$message.error(data.msg);
                            }
                            // console.log(data);
                        });
                    })
                    .catch(_ => {

                    });
            },

            seeCourseBut(id){
                this.$router.push('/user/course/type/one/'+id);
            }

        },
        mounted(){
            this.getType();
            this.getData();
        }
    }
</script>

<style scoped>

</style>