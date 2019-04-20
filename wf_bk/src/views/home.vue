<template>
	<el-container>
		<el-dialog title="修改密码" :visible.sync="dialogFormVisible" :width="'40%'">
			<div class="input-item">
				<el-input type="password" placeholder="输入原始密码" v-model="oldPwd" :clearable="true">
					<template slot="prepend">
						&ensp;&ensp;原密码&ensp;&ensp;
					</template>
				</el-input>
			</div>
			<div class="input-item">
				<el-input type="password" placeholder="输入新密码" v-model="newPwd" :clearable="true">
					<template slot="prepend">
						&ensp;&ensp;新密码&ensp;&ensp;
					</template>
				</el-input>
			</div>
			<div class="input-item">
				<el-input type="password" placeholder="确认新密码" v-model="newRePwd" :clearable="true">
					<template slot="prepend">
						确认新密码
					</template>
				</el-input>
			</div>
			<div slot="footer" class="dialog-footer">
				<el-button @click="dialogFormVisible = false">取 消</el-button>
				<el-button type="primary" @click="recodePwdEvent">确 定</el-button>
			</div>
		</el-dialog>
		<el-aside style="width: auto">
			<img @click="isCollapse = !isCollapse" src="../assets/timg.jpg" :width="isCollapse ? 64 : 200" alt="" />
			<el-menu 
			:default-active="$route.path" 
			@select="selectOne" 
			:collapse="isCollapse">
				<el-menu-item index="/user">
					<i class="el-icon-menu"></i>
					<span slot="title">用户管理</span>
				</el-menu-item>
				<el-menu-item index="/side">
					<i class="el-icon-location-outline"></i>
					<span slot="title">地址管理</span>
				</el-menu-item>
				<el-menu-item index="/hotcity">
					<i class="el-icon-location"></i>
					<span slot="title">热门城市</span>
				</el-menu-item>
			</el-menu>
		</el-aside>
		<el-container>
			<el-header>
				<el-breadcrumb separator-class="el-icon-arrow-right">
					<el-breadcrumb-item :to="{ path: '/home' }">首页</el-breadcrumb-item>
					<el-breadcrumb-item v-text="changePath($route.path)" v-if="changePath($route.path)"></el-breadcrumb-item>
				</el-breadcrumb>
				<el-dropdown @command="logout">
					<div class="dropdown-hd">
						<img src="../assets/timg.jpg" height="20" alt="" />
						<span v-text="adminName"></span>
						<i class="el-icon-arrow-down el-icon--right"></i>
					</div>
					<el-dropdown-menu slot="dropdown">
						<el-dropdown-item icon="el-icon-edit-outline" command="recodePwd">修改密码</el-dropdown-item>
						<el-dropdown-item icon="iconfont icon-exit" :divided="true" command="logout">退出登录</el-dropdown-item>
					</el-dropdown-menu>
				</el-dropdown>
				<div class="m-pg" v-show="$store.getters.getpProgressStatus"><el-progress :percentage="progressState" :show-text="false" :stroke-width="2"></el-progress></div>
			</el-header>
			<el-main>
				<router-view />
				<div class="page-area">
					<el-pagination
						background
						layout="prev, pager, next"
						:page-size="$store.getters.getPageSize"
						:current-page="$store.getters.getPage"
						@current-change="pageChange"
						:total="$store.getters.getRowsCount"
					></el-pagination>
				</div>
			</el-main>
			<el-footer>
				<span>桃李不言&nbsp;&nbsp;下自成蹊</span>
				<span>&copy;版权所有&nbsp;韫泽</span>
			</el-footer>
		</el-container>
	</el-container>
</template>
<script>
export default {
	data() {
		return {
			isCollapse: true,
			nowSide: '',
			dialogFormVisible: false,
			oldPwd: '',
			newPwd: '',
			newRePwd: '',
			adminName: '',
			adminCode: '',
			phone: ''
		};
	},
	created() {
		this.checkIsLogin();
	},
	methods: {
		selectOne(tag, menu) {
			this.$router.push(tag);
		},
		changePath(path) {
			switch (path) {
				case '/user':
					return '用户管理'
				case '/side':
					return '地址管理'
					case '/hotcity':
					return '热门城市'
				default:
					return
			}
		},
		logout(command) {
			var vm = this
			if (command == 'logout') {
				vm.$confirm('是否退出系统?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'danger'
				}).then(() => {
					localStorage.removeItem('adminInfo')
					vm.$message({
						type: 'success',
						message: '退出成功!',
						duration: 1000,
						onClose() {
							vm.$router.push('/')
						}
					})
				})
			} else if (command == 'changePwd') {
				localStorage.removeItem('adminInfo')
				vm.$router.push('/')
			} else {
				vm.oldPwd = ''
				vm.newPwd = ''
				vm.newRePwd = ''
				vm.dialogFormVisible = true
			}
		},
		recodePwdEvent() {
			var vm = this,
				code = vm.adminCode,
				oldPwd = vm.oldPwd,
				newPwd = vm.newPwd,
				newRePwd = vm.newRePwd,
				phone = vm.phone
			if (!oldPwd || !newPwd || !newRePwd) {
				vm.$message({
					message: '修改密码不能有空值出现',
					duration: 1200,
					type: 'info'
				})
				return
			}
			if (newPwd != newRePwd) {
				vm.$message({
					message: '两次新密码输入不一致',
					duration: 1200,
					type: 'warning'
				})
				return
			}
			vm.$req(
				'post',
				{
					c: 'admin',
					f: 'changeadminpwd',
					code,
					oldPwd,
					newPwd,
					phone
				},
				res => {
					if (res.state == 1) {
						vm.$message({
							message: '密码修改成功',
							duration: 1200,
							type: 'success',
							onClose() {
								vm.dialogFormVisible = false
								vm.logout('changePwd')
							}
						})
					} else {
						vm.$message({
							message: '密码修改失败',
							duration: 1200,
							type: 'info',
							onClose() {
								vm.dialogFormVisible = false
							}
						})
					}
				}
			)
		},
		checkIsLogin() {
			var admin = localStorage.adminInfo,
				vm = this
			if (!admin) {
				vm.$notify({
					title: '提示',
					message: '管理员信息失效，请重新登录',
					type: 'info',
					duration: 1000,
					onClose() {
						vm.$router.push('/')
					}
				})
			} else {
				admin = JSON.parse(admin)
				vm.adminName = admin.name
				vm.adminCode = admin.code
				vm.phone = admin.phone
			}
		},
		pageChange(page) {
			this.$store.commit('setPage', page)
		}
	},
	computed: {
		progressState() {
			return this.$store.getters.getProgressPage
		}
	},
	watch: {
		$route() {
			this.checkIsLogin()
			this.$store.commit('setPage', 1)
		}
	}
}
</script>
<style lang="less" scoped>
.el-container {
	.el-container {
		margin-left: -1px;
	}
}
@import '../config.less';
.input-item {
	margin-bottom: 4px;
}
.dropdown-hd {
	display: flex;
	@{ai}: center;
	span {
		margin-left: 10px;
	}
	img {
		@{bdra}: 12px;
	}
	&:hover {
		cursor: pointer;
	}
}
.el-header {
	position: relative;
	@{bgc}: @info-small;
	.m-pg {
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
	}
	display: flex;
	@{ai}: center;
	@{jc}: space-between;
}
.el-main {
	min-height: 800px;
	/* @{bgc}: #f82; */
}
.el-aside {
	@{bgc}: @success-little;
	.iconfont {
		margin-right: 20px;
	}
	img {
		transition: width 0.5s linear, height 0.5s linear;
	}
}
.el-footer {
	display: flex;
	@{fd}: column;
	@{ai}: center;
	@{jc}: space-around;
	@{bgc}: @info-little;
	font-family: '楷体';
	@{fs}: 14px;
}
.page-area {
	display: flex;
	@{jc}: flex-end;
	padding: 20px 40px 20px 0;
}
</style>
