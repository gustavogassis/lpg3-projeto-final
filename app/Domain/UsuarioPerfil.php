<?php

namespace App\Domain;

enum UsuarioPerfil: string
{
    case ADMINISTRADOR = 'administrador';
    case PROFESSOR = 'professor';
    case ALUNO = 'aluno';
}
