<?php
// Mascarás de qualquer natureza
function mascara($variavel, $mascara)
{
    $caracters = ['.', '-'];
    $limpa_formatacao = str_replace($caracters, '', $variavel);
    $variavel = $limpa_formatacao;
    $resultado = '';
    $k = 0;
    for ($i = 0; $i <= strlen($mascara) - 1; ++$i) {
        if ($mascara[$i] == '#') {
            if (isset($variavel[$k])) {
                $resultado .= $variavel[$k++];
            }
        } else {
            if (isset($mascara[$i])) {
                $resultado .= $mascara[$i];
            }
        }
    }

    return $resultado;
}

function removeMascaraCpf($variavel)
{
    $explode = ['.','-'];
    $variavel_sem_mascara = str_replace($explode, '', $variavel);
    return $variavel_sem_mascara;
}