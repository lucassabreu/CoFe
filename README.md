CoFe
----

CoFe é uma aplicação desenvolvida para confirmar o aprendizado adiquirido com o 
curso "ZF2 na prática" (http://code-squad.com/curso/zf2-na-pratica).

Também objetiva gerar uma maior intimidade com o Zend Framework.

Entidades
---------
Para esse projeto serão utilizadas as seguintes entidades:

    - Usuário (user)
        - Código (id) [INCREMENT]
		- Nome de Usuário (username)
		- Senha (password) [MD5(?)]
		- Ativo (active) [0|1]
		- Função (role) [admin|common]
		- Ultima Atividade (last_activity) [DATETIME]
		- Nome (name)
		- E-mail (email)
		- Data Criação (dt_criation)
		
	- Categoria (category)
		- Usuário (user_id)
		- Categoria (code)
		- Descrição (descrition)
		- Tipo Fluxo (flow_type) [I|O]
		
	- Movimento (moviment)
		- Código (id) [INCREMENT]
		- Usuário (user_id)
		- Categoria (cat_code)
		- Valor (value) [18,2]
		- Data Movimento (dt_emission)
		- Descrição (description) 
		- Notas (notes) [TEXT]

Composer
--------

Para poder utilizar esse projeto será necessário antes de tudo atualizar os pacotes
usando o composer. Segue abaixo o comando que realiza a ação.

    cd %diretório_clone%
    php composer.phar self-update
    php composer.phar install