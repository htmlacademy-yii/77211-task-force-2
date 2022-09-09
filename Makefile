migrate:
	php yii migrate --interactive=0

seed:
	php yii fixture/load "*" --interactive=0

auth:
	php yii migrate --migrationPath=@yii/rbac/migrations --interactive=0
	php yii rbac/init

init:
	make migrate
	make seed
	make auth