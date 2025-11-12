create table usuarios (
      id int unsigned auto_increment primary key,
      nome varchar(80) not null,
      email varchar(80) unique not null,
      senha_hash varchar(255) not null,
      perfil enum('administrador', 'professor', 'aluno') not null
);

create table alunos (
    id int unsigned auto_increment primary key,
    usuario_id int unsigned unique not null,
    matricula varchar(30) unique not null,
    foreign key (usuario_id) references usuarios(id) on delete cascade
);

create table professores (
     id int unsigned auto_increment primary key,
     usuario_id int unsigned unique not null,
     departamento varchar(50),
     foreign key (usuario_id) references usuarios(id) on delete cascade
);

create table disciplinas (
     id int unsigned auto_increment primary key,
     nome varchar(80) not null,
     codigo varchar(20) unique not null
);

create table periodos (
      id int unsigned auto_increment primary key,
      ano int not null,
      semestre int not null,
      unique(ano, semestre)
);

create table turmas (
    id int unsigned auto_increment primary key,
    disciplina_id int unsigned not null,
    professor_id int unsigned not null,
    periodo_id int unsigned not null,
    status_turma enum('aberta', 'encerrada') not null default 'aberta',

    foreign key (disciplina_id) references disciplinas(id),
    foreign key (professor_id) references professores(id),
    foreign key (periodo_id) references periodos(id)
);


create table matriculas (
    id int unsigned auto_increment primary key,
    aluno_id int unsigned not null,
    turma_id int unsigned not null,
    unique(aluno_id, turma_id),
    foreign key (aluno_id) references alunos(id),
    foreign key (turma_id) references turmas(id)
);


create table notas (
   id int unsigned auto_increment primary key,
   matricula_id int unsigned not null,
   descricao varchar(100) not null,
   valor decimal(5, 2) not null,
   foreign key (matricula_id) references matriculas(id) on delete cascade
);