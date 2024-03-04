## Porque do projeto

Mypoint simula o básico de um aplicativo de RH, onde o funcionário pode registrar suas marcações, pedir que elas sejam revisadas pela empresa e consultar se fez hora extra ao final do dia.
O projeto foi feito com o objetivo de conhecer os recursos disponilizados pelo filament, e fixar as principais features.

## Explicações sobre o funcionamento

O app tem 3 níveis de permissão ``` Master, Admin, Employee ```.
O usuário Master atualmente na seeder é andreew@onhappy.com, junto com duas empresas.
Para obter esses dados basta rodar o comando --seed na frente do comando de migration ```sail artisan migrate:fresh --seed ```.
Por padrão o filament possibilita a restringir apenas emails aprovados a fazer login e também verifica se o email do usuário já esta verificado (A criação de usuários implementada já faz esse tratamento).
Os email disponiveis no projeto são esses ![Screenshot from 2024-03-04 16-51-25](https://github.com/Andreewkj/mypoint/assets/62602623/e4ff3360-b7e7-4e84-9de4-aa6a308a8e09)
Para modificar esse trecho de código basta ir até o Model User.
No projeto foi usado a versão 3.0-stable do filament, pois a versão mais recente não configurou corretamente.


## Casos de uso
Um usuário master pode criar outros usuários e editar (Caso passe a senha vazia na edição, a senha atual do usuário será mantida)
![Screenshot from 2024-03-04 17-00-27](https://github.com/Andreewkj/mypoint/assets/62602623/265d4462-c4ab-43dc-beaa-52d9e061a05e)

Um usuário ADM, pode aprovar e reprovar revisões de ponto dos usuário de primeiro nivel, pode bater seu próprio ponto e para editar pontos de sua empresa, não precisa de aprovação.
Ele só pode visualizar os usuário que estão cadastrados em sua empresa, porém quem edita é só o master.
![Screenshot from 2024-03-04 17-03-45](https://github.com/Andreewkj/mypoint/assets/62602623/f93539b3-933f-46ca-a554-272c4bd13e9a)

O usuário de primeiro nivel tem acesso as horas que trabalhou através dos widges no menu de dashboard.
As horas são contabilizadas apenas se o usuário registrou no minimo 4 marcações no dia, e será descontado do total 1 hora de almoço.
Caso o número de registros esteja errado (ímpar), também será mostrado no dashboard.
![Screenshot from 2024-03-04 16-38-02](https://github.com/Andreewkj/mypoint/assets/62602623/6e67113c-3b28-4c9e-bae8-a9c9abd1a7d9)


[MIT license](https://opensource.org/licenses/MIT).
