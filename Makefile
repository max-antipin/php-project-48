lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin/* && \
	composer exec --verbose phpstan analyze

fix-format:
	composer exec --verbose phpcbf -- --standard=PSR12 src bin/*
