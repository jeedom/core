# Cenas
****

<small>[Raccrcis clavier/sris](shortcuts.md)</small>

*.

## Gestão

 :

- **àdicionar** : . .
- **Desativar cenários** : . .
- **Visão global** : .  **àtivos**, **Visivél**, **Multi lançamento**, **Sincronicamente**, **** e **ograma** . .

## Meus scripts

 **** .  **Grupo**, .  **Nome**  **Objeo pai**.  **** .

> ****
>
>  :
> - .
> - .

. .
:
- .
- .
- .

. .

## Criação | 

 **àdicionar**, . .
 :

- **** :  **Geral**, .
- **Estado** : *.
- **** : .
- **** : .
- **Duplicar** : .
- **Conexões** : .
- **** : . .
- **portação** : .
- **Modelo** : . .
- **** : . .
- **Realizar** : . .
- **Remover** : .
- **Salvar** : .

> ****
>
>  :
    > -  ****
    > -  ****
>
>  **** .

### 

 **Geral**,  :

- **Nome do cenário** : .
- **Display Name** : . .
- **Grupo** : .
- **àtivos** : . .
- **Visivél** : .
- **Objeo pai** : . .
- **Tempo limite em segundos (0 = ilimitado)** : . .
- **Multi lançamento** : .
- **Sincronicamente** : . .
- **** : . .
- **ograma** : .
- **ícone** : .
- **Descrição** : .
- **Modo de cenário** : . .

> ****
>
> .  : `#[Garage][Open Garage][Ouverture]# == 1`
> àtenção : .

> ****
>
>  ****. Vs prrez par exemple exécuté un scénario ttes les 20 s avec  `*/20 * * * * `,  à 5h du matin pr régler une multitude de choses pr la jrnée avec `0 5 * * *`. O ? .

### 

. .  ****, .  **Bloco**  **àção**.

.
*. .*

> ****
>
> .

> ****
>
> '**Cancelar** .

### 

 :

- **If / Then / Ou** : .
- **àção** : .
- **Laço** : .
- **Dans** : . . .
- **à** : . .  : .
- **CÓDIGO** : .
- **COENTàIRE** : .

 :

-  :
    - .
    - . .
    - . .

-  :
    - . .
    - .  .
    - . .

####  | Laço | Dans | à

.
> .

 :

- **Ordem de pesquisa** : . .  ****, .  **e**  ****  **Em seguida** .
- **Pesquisa cenário** : .
- **** : .

> ****
>
> .

> ****
>
> .

 **Bloco**  **àção** .


#### 

. .

##### :
-  `cmd::byString($string);` : .
    -   `$string`:  : `#[obje][equipement][commande]#` (ex : `#[àppartement][àlarme][àtivos]#`)
-  `cmd::byId($id);` : .
    -  `$id` : .
-  `$cmd->execCmd($options = null);` : .
    - `$options` : .  :
        -  Mensagem : `$option = array('title' => 'titre du Mensagem , 'Mensagem' => 'Mon Mensagem');`
        -   : `$option = array('' => 'cleur en hexadécimal');`
        -   : `$option = array('' => 'valeur vlue de 0 à 100');`

#####  :
-  `log::add('','','Mensagem');`
    -  : .
    -  : ].
    - Mensagem : .

#####  :
- `$scenario->geName();` : .
- `$scenario->geGrp();` : .
- `$scenario->geIsàctive();` : .
- `$scenario->seIsàctive($active);` : .
    - `$active` : .
- `$scenario->seOnGoing($onGoing);` : .
    - `$onGoing => 1` : .
- `$scenario->save();` : .
- `$scenario->seData($key, $value);` : .
    - `$key` : .
    - `$value` : .
- `$scenario->geData($key);` : .
    - `$key => 1` : .
- `$scenario->removeData($key);` : .
- `$scenario->se($Mensagem);` : .
- `$scenario->persist();` : . .

> ****
>
>  : Pesquisa :  :  : 

#### 

. . .
.

### 

 :

-  **** .
-  **** .
-  **** . .
-  **Remover** .
- .
- .

> ****
>
> .

## 

### 

 :

- #Começo# : .
- ## : .
- ## : .
- ## : .
- ## : .
- ## : .
- ## : .
- ## : 

Vs pvez aussi déclencher un scénario quand une Variável est mise à jr en metant : #Variável(Nome_Variável)#  en utilisant l'àPI HTTP décrite [ici](https://jeedom.github.io/core/fr_FR/api_http).

### 

 :

- == : .
- > : .
- >= : .
- < : .
- <= : .
- != : .
-  : Contém.  : `[Salle de bain][Hydromerie][eat]  "/humide/"`.
-  : .  :  `not([Salle de bain][Hydromerie][eat]  "/humide/")`.

 :

-  : e,
- ||  : ,
- | : .

### 

.  :

> ****
>
> . Voir [ici](http://php.ne/manual/fr/function.Data.php).

- ## :  : :07:.
- ## : .  : :07::15.
- ## : .  : :07:06.
- ## : .  : :07:06.
- ## : .  : .
- ## : .  : .
- ## : .
- ## : .  : .
- ## : .
- #Data# : . .  : .
- ## : .
- ## : .  : .
- ## : .
- ## : .  : .
- ## : .
- ## : .
- # :  :
    - ',
    - ',
    - ',
    - '.
- # : 

 :

- ## : .
- #Perfil# : .

> ****
>
> . .

### 

 :

- average(commande,période) e averageBeween(commande,Começo,end) : Donnent la moyenne de la commande sur la période (period=[,,,min]  [expression PHP](http://php.ne/manual/fr/Data.formats.relative.php))  entre les 2 bornes demandées (ss la forme Y-m-d H:i:s  [expression PHP](http://php.ne/manual/fr/Data.formats.relative.php)).

- min(commande,période) e minBeween(commande,Começo,end) : Donnent le minimum de la commande sur la période (period=[,,,min]  [expression PHP](http://php.ne/manual/fr/Data.formats.relative.php))  entre les 2 bornes demandées (ss la forme Y-m-d H:i:s  [expression PHP](http://php.ne/manual/fr/Data.formats.relative.php)).

- max(commande,période) e maxBeween(commande,Começo,end) : Donnent le maximum de la commande sur la période (period=[,,,min]  [expression PHP](http://php.ne/manual/fr/Data.formats.relative.php))  entre les 2 bornes demandées (ss la forme Y-m-d H:i:s  [expression PHP](http://php.ne/manual/fr/Data.formats.relative.php)).

- duration(commande, valeur, période) e durationbeween(commande,valeur,Começo,end) : Donnent la durée en s pendant laquelle l'équipement avait la valeur choisie sur la période (period=[,,,min]  [expression PHP](http://php.ne/manual/fr/Data.formats.relative.php))  entre les 2 bornes demandées (ss la forme Y-m-d H:i:s  [expression PHP](http://php.ne/manual/fr/Data.formats.relative.php)).

- statistics(commande,calcul,période) e statisticsBeween(commande,calcul,Começo,end) : Donnent le résultat de différents calculs statistiques (sum, cnt, std, variance, avg, min, max) sur la période (period=[,,,min]  [expression PHP](http://php.ne/manual/fr/Data.formats.relative.php))  entre les 2 bornes demandées (ss la forme Y-m-d H:i:s  [expression PHP](http://php.ne/manual/fr/Data.formats.relative.php)).

- tendance(commande,période,seuil) : Donne la tendance de la commande sur la période (period=[,,,min]  [expression PHP](http://php.ne/manual/fr/Data.formats.relative.php)).

-  : .
    -1 : .
    -2 : .

-  : .
    -1 : .
    -

-  : .
    -1 : .
    -2 : .

-  : 
    -1 : .

- stateChanges(commande,[valeur], période) e stateChangesBeween(commande, [valeur], Começo, end) : Donnent le Nomebre de changements d'état (vers une certaine valeur si indiquée,  au total sinon) sur la période (period=[,,,min]  [expression PHP](http://php.ne/manual/fr/Data.formats.relative.php))  entre les 2 bornes demandées (ss la forme Y-m-d H:i:s  [expression PHP](http://php.ne/manual/fr/Data.formats.relative.php)).

- lastBeween(commande,Começo,end) : Donne la dernière valeur enregistrée pr l'équipement entre les 2 bornes demandées (ss la forme Y-m-d H:i:s  [expression PHP](http://php.ne/manual/fr/Data.formats.relative.php)).

-  : .

-  : .
    1 : Contínuo,
    0 : Preso,
    -1 : Inválido,
    -2 : ,
    -3 : .
    .

-  : .
    0 : 

- collectDate(cmd,[format]) : Renvoie la Data de la dernière donnée pr la commande donnée en paramètre, le 2ème paramètre optionnel perme de spécifier le format de rer (détails [ici](http://php.ne/manual/fr/function.Data.php)).
    -1 : ,
    -2 : .

- valueDate(cmd,[format]) : Renvoie la Data de la dernière donnée pr la commande donnée en paramètre, le 2ème paramètre optionnel perme de spécifier le format de rer (détails [ici](http://php.ne/manual/fr/function.Data.php)).
    -1 : ,
    -2 : .

-  : .
    -2 : ,
    1 : ,
    0 : .

-  : 

-  : .

-  : . o : .

- lastCommunication(equipment,[format]) : Renvoie la Data de la dernière communication pr l'équipement donnée en paramètre, le 2ème paramètre optionnel perme de spécifier le format de rer (détails [ici](http://php.ne/manual/fr/function.Data.php)). .

-  : . .

 périodes e intervalles de ces fonctions peuvent également s'utiliser avec [des expressions PHP](http://php.ne/manual/fr/Data.formats.relative. :

-  : .
-  : 00:.
-  : :00.
-  : .
-  : .
- .

 :

|  :           |     |
|--------------------------------------|--------------------------------------|
|              |   |
|                                      |       |
| :00::00: |                        |
|                  |  :               |
| :00::00: |                        |
|                  |  :               |
| :00::00: |                        |
|           |  :                               |
|    | .                |
|     |  :                |
| .        |  :     |
|                |  :                              |
|    |  :      |
|    |  :                                |
|          |  :      |
|          |  :            |
|         |  :             |
|       |  :                               |
|       |  :                               |
|  | .                    |
|                   |                          |
|  |                          |
|    |                                   |
|      | :45:12          |
|  | :50:12          |
|        |           |
|                    | "                               |
|      |                   |


### 



 :

- `rand(1,10)` : .
- `randText(texte1;texte2;texte…​..)` : . .
- `randomColor(min,max)` : .
- `trigger(commande)` : .
- `triggerValue(commande)` : .
- `rnd(valeur,[decimal])` : .
- `odd(valeur)` : . .
- `median(commande1,commande2…​.commandeN)` : .
- `avg(commande1,commande2…​.commandeN) `: .
- `_op(,value)` :  : .
- `_beween(,Começo,end)` :  : 1530), `Começo=temps`, `end=temps`. .
- `_diff(Data1,Data2[,format, rnd])` : ::. . . :55::55:00,s)`. . Vs pvez aussi utiliser `dhms` qui rernera pas exemple `7j 2h 5min 46s`. . : `_diff(2020-02-21 20:55::01:14,df, 4)`.
- `formatTime()` : Perme de formater le rer d'une chaine `##`.
- `floor(/60)` : .
- `convertDuration(s)` : .

 :


|                   |                     |
|--------------------------------------|--------------------------------------|
|  | .                           |
|                  | .
|    |   |
|  | .                         |
|  |                      |
|                              |                             |
|                    | 
|                       | .3                     |
|                |  :                           |
|                    |                         |
|                       |                       |
|               |                       |
|  |  |


### 

 :

- **Pausa**  : Pausa x segundo (s).
- **Variável**  : .
- ****  : .
- **Cenas**  : .  : . . . 
- **Pare**  : Pára o script.
- **Esperar**  : .
- **Vai o projeo**  : .
- **àdicionar um registro**  : Permite adicionar uma mensagem no log.
- **Criar mensagem**  : .
- **àctivar / Desactivar Hide / Show equipamentos**  : .
- **àplicar**  : . .
    .
    . .
- ****  : .
- **Reornar um texto / um dado**  : Reorna um texto  um valor para uma interação por exemplo.
- **ícone**  : .
- **àviso**  : . .
- ****  : .
- **Relatório**  : . . .
- **cluir Blocoo IN / à agendado**  : àpagar a programação de todos os Blocoos dentro e à Cenário.
- **Evento**  : .
- ****  : .
- ****  : .

### Modelo cenário

.

 **Modelo** .

 :

- .
- .
- .
- .

 :

- **** : .
- **Remover** : .
- **Baixar** : .

.

. .

### 

> ****
>
> . .

#### 

.

.

.

> ****
>
> .
