<template>
    <div >
        <div style="margin-top: 20px;">
            <label >用户：</label>
            <span>{{ user.name }}</span>
        </div>

        <div>
            <div>
                <el-button v-if="$route.path === '/user/course/type/two/'+$route.params.id" type="primary" @click="changeType('/user/course/type/one/'+$route.params.id)">格式一</el-button>
                <el-button v-if="$route.path === '/user/course/type/one/'+$route.params.id || $route.path === '/user/course'" type="primary" @click="changeType('/user/course/type/two/'+$route.params.id)">格式二</el-button>
                <el-button type="primary" @click="addCourseVisible = true">添加课程</el-button>
                <el-button type="primary" @click="clearAllCourse">清除所有课表</el-button>
            </div>
        </div>


        <router-view @courseInfo="userInfo" :userId="userId" :courseInfos="courseInfos">

        </router-view>

        <el-dialog title="添加课程" :visible.sync="addCourseVisible" class="dialog-width">
            <el-form :model="newCourse" label-width="80px" :rules="rules" ref="newCourse" style="width: 460px;" >
                <el-form-item label="课程名称" prop="name">
                    <el-input v-model="newCourse.name">

                    </el-input>
                </el-form-item>
                <el-form-item label="地点" prop="location">
                    <el-input v-model="newCourse.location">

                    </el-input>
                </el-form-item>

                <el-form-item label="星期" prop="week_day">
                    <el-select v-model="newCourse.week_day" placeholder="请选择" style="width: 100%;">
                        <el-option :key="1" label="星期一" :value="1">

                        </el-option>
                        <el-option :key="2" label="星期二" :value="2">

                        </el-option>
                        <el-option :key="3" label="星期三" :value="3">

                        </el-option>
                        <el-option :key="4" label="星期四" :value="4">

                        </el-option>
                        <el-option :key="5" label="星期五" :value="5">

                        </el-option>
                        <el-option :key="6" label="星期六" :value="6">

                        </el-option>
                        <el-option :key="7" label="星期日" :value="7">

                        </el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="节数">
                    <el-col :span="11">
                        <el-form-item prop="start_section">
                            <el-select v-model="newCourse.start_section"  placeholder="开始"  @change="startSectionChange(0)">
                                <el-option :key="1" label="第1节" :value="1"></el-option>
                                <el-option :key="3" label="第3节" :value="3"></el-option>
                                <el-option :key="5" label="第5节" :value="5"></el-option>
                                <el-option :key="7" label="第7节" :value="7"></el-option>
                                <el-option :key="9" label="第9节" :value="9"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="2" style="text-align: center;">—</el-col>
                    <el-col :span="11">
                        <el-form-item prop="end_section">
                            <el-select v-model="newCourse.end_section" placeholder="结束">
                                <el-option v-for="section in end_sections"
                                           :key="section"
                                           :label="'第'+section+'节'"
                                           :value="section" v-if="section >= newCourse.start_section">

                                </el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-form-item>

                <el-form-item label="周数">
                    <el-col :span="6">
                        <el-form-item prop="start_week">
                            <el-select v-model="newCourse.start_week" placeholder="开始" @change="startWeekChange(0)">
                                <el-option v-for="n in 20" :key="n" :label="'第'+n+'周'" :value="n">

                                </el-option>
                            </el-select>
                        </el-form-item>

                    </el-col>
                    <el-col :span="2" style="text-align: center;">—</el-col>
                    <el-col :span="6">
                        <el-form-item prop="end_week">
                            <el-select v-model="newCourse.end_week" placeholder="结束">
                                <el-option v-for="week in end_weeks"
                                           :key="week"
                                           :label="'第'+week+'周'"
                                           :value="week" v-if="week >= newCourse.start_week">

                                </el-option>
                            </el-select>
                        </el-form-item>

                    </el-col>
                    <el-col :span="4" style="text-align: right;padding-right: 3px;">类型:</el-col>
                    <el-col :span="6">
                        <el-form-item prop="status">
                            <el-select v-model="newCourse.status" placeholder="类型">
                                <el-option key="0" label="全部" :value="0">

                                </el-option>
                                <el-option key="1" label="单周" :value="1">

                                </el-option>
                                <el-option key="2" label="双周" :value="2">

                                </el-option>
                            </el-select>
                        </el-form-item>

                    </el-col>
                </el-form-item>

            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="addCourseVisible = false">取 消</el-button>
                <el-button @click="resetForm('newCourse')">重 置</el-button>
                <el-button type="primary" @click="addSubmit('newCourse')">添 加</el-button>
            </div>
        </el-dialog>


    </div>
</template>

<script>
    export default {
        data() {
            return {
                userId: 0,
                user:{
                    name:'',
                },

                addCourseVisible: false,

                courseInfos: [],
                newCourse: {
                    name: '',
                    location: '',
                    week_day:1,
                    start_week:1,
                    end_week:1,
                    status: 0,
                    start_section: 1,
                    end_section: '',
                },


                end_weeks: [
                    1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,
                ],
                end_sections: [
                    2,4,6,8,10
                ],

                rules: {
                    name: [
                        { required: true, message: '请输入课程名称', trigger: 'blur' },
                        { max: 50, message: '长度在50个字符内', trigger: 'blur' }
                    ],
                    location: [
                        { required: true, message: '请输入上课地点', trigger: 'blur' },
                        { max: 50, message: '长度在50个字符内', trigger: 'blur' }
                    ],
                    week_day: [
                        { required: true, message: '请选择星期' }
                    ],
                    start_week: [
                        { required: true, message: '请选择开始周' }
                    ],
                    end_week: [
                        { required: true, message: '请选择结束周' }
                    ],
                    start_section: [
                        { required: true, message: '请选择开始节数' }
                    ],
                    end_section: [
                        { required: true, message: '请选择结束节数' }
                    ],
                    status: [
                        { required: true, message: '请选择周类型' }
                    ]
                },

            }
        },
        methods: {
            userInfo(){
                this.userId = this.$route.params.id;
                axios.post('/admin/user/course/get', {
                    id: this.userId,
                }).then(res => {
                    let data = res.data;
                    if(data.code === 1){
                        this.user = data.data;
                        this.courseInfos = this.user.courses;
                        console.log(data);
                    }else{
                        this.$message.error(data.msg);
                    }
                });
            },
            changeType(url) {
                this.$router.push(url);
                // this.userInfo();
            },

            startSectionChange(type){

                if(type === 0){
                    if(this.newCourse.end_section < this.newCourse.start_section) this.newCourse.end_section = '';
                }else{
                    if(this.editCourse.end_section < this.editCourse.start_section) this.editCourse.end_section = '';
                }

            },

            startWeekChange(type){
                if(type === 0){
                    if(this.newCourse.end_week < this.newCourse.start_week) this.newCourse.end_week = '';
                }else{
                    if(this.editCourse.end_week < this.editCourse.start_week) this.editCourse.end_week = '';
                }
            },

            resetForm(formName) {
                this.$refs[formName].resetFields();
            },

            addSubmit(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        axios.post('/admin/user/course/add',{
                            id:  this.userId,
                            name:       this.newCourse.name,
                            location:   this.newCourse.location,
                            week_day:   this.newCourse.week_day,
                            week_start: this.newCourse.start_week,
                            week_end:   this.newCourse.end_week,
                            week_type:     this.newCourse.status,
                            section_start:  this.newCourse.start_section,
                            section_end:    this.newCourse.end_section,
                        }).then(response => {
//                            console.log(response.data);
                            let data = response.data;
                            if(parseInt(data.code) === 0){
                                this.$message.error(data.msg);
                            }else{
                                this.$message({
                                    message: '添加成功',
                                    type: 'success'
                                });

                                this.userInfo();
                                this.addCourseVisible = false;
                                this.$refs[formName].resetFields();
                            }
                        });
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },


            clearAllCourse(){
                this.$confirm('此操作将清除所有课程, 是否继续?', '提示', {
                    confirmButtonText: '继续',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    axios.post('/admin/user/course/clear',{
                        'id': this.userId,
                    }).then(response => {
//                            console.log(response.data);
                        let data = response.data;
                        if(parseInt(data.code) === 0){
                            this.$message.error(data.msg);
                        }else{
                            this.$message({
                                message: '清除成功',
                                type: 'success'
                            });
                            this.userInfo();
                        }
                    });
                });
            }
        },

        mounted(){
            this.userInfo();
        }

    }
</script>

<style>
    .dialog-width>div{
        min-width: 520px;
    }
</style>