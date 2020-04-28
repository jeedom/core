# Cenas
****

<small>[Raccrcs claver/srs](shortcuts.md)</small>

*.

## Gestão

 :

- **àdconar** : . .
- **Desatvar cenáros** : . .
- **Vsão global** : .  **àtvos**, **Vsvél**, **Mult lançamento**, **Sncroncamente**, **** e **ograma** . .

## Meus scrpts

 **** .  **Grupo**, .  **Nome**  **Objeo pa**.  **** .

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

## Cração | 

 **àdconar**, . .
 :

- **** :  **Geral**, .
- **Estado** : *.
- **** : .
- **** : .
- **Duplcar** : .
- **Conexões** : .
- **** : . .
- **portação** : .
- **Modelo** : . .
- **** : . .
- **Realzar** : . .
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

- **Nome do cenáro** : .
- **Dsplay Name** : . .
- **Grupo** : .
- **àtvos** : . .
- **Vsvél** : .
- **Objeo pa** : . .
- **Tempo lmte em segundos (0 = lmtado)** : . .
- **Mult lançamento** : .
- **Sncroncamente** : . .
- **** : . .
- **ograma** : .
- **ícone** : .
- **Descrção** : .
- **Modo de cenáro** : . .

> ****
>
> .  : `#[Garage][Open Garage][Ouverture]# == 1`
> àtenção : .

> ****
>
>  ****. Vs prrez par exemple exécuté un scénaro ttes les 20 s avec  `*/20 * * * * `,  à 5h du matn pr régler une multtude de choses pr la jrnée avec `0 5 * * *`. O ? .

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

- **Ordem de pesqusa** : . .  ****, .  **e**  ****  **Em seguda** .
- **Pesqusa cenáro** : .
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
-  `cmd::byStrng($strng);` : .
    -   `$strng`:  : `#[obje][equpement][commande]#` (ex : `#[àppartement][àlarme][àtvos]#`)
-  `cmd::byId($d);` : .
    -  `$d` : .
-  `$cmd->execCmd($optons = null);` : .
    - `$optons` : .  :
        -  Mensagem : `$opton = array('ttle' => 'ttre du Mensagem , 'Mensagem' => 'Mon Mensagem');`
        -   : `$opton = array('' => 'cleur en hexadécmal');`
        -   : `$opton = array('' => 'valeur vlue de 0 à 100');`

#####  :
-  `log::add('','','Mensagem');`
    -  : .
    -  : [debug], [nfo], [error], [event].
    - Mensagem : .

#####  :
- `$scenaro->geName();` : .
- `$scenaro->geGrp();` : .
- `$scenaro->geIsàctve();` : .
- `$scenaro->seIsàctve($actve);` : .
    - `$actve` : .
- `$scenaro->seOnGong($onGong);` : .
    - `$onGong => 1` : .
- `$scenaro->save();` : .
- `$scenaro->seData($key, $value);` : .
    - `$key` : .
    - `$value` : .
- `$scenaro->geData($key);` : .
    - `$key => 1` : .
- `$scenaro->removeData($key);` : .
- `$scenaro->se($Mensagem);` : .
- `$scenaro->persst();` : . .

> ****
>
>  : Pesqusa :  :  : 

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

 : #:.

### 

 :

- == : .
- > : .
- >= : .
- < : .
- <= : .
- != : .
-  : Contém.  : `[Salle de ban][Hydromere][eat]  "/humde/"`.
-  : .  :  `not([Salle de ban][Hydromere][eat]  "/humde/")`.

 :

-  : e,
- ||  : ,
- | : .

### 

.  :

> ****
>
> . :.

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
- #Perfl# : .

> ****
>
> . .

### 

 :

-  : :.:::.

-  : :.:::.

-  : :.:::.

-  : :.:::.

-  : :.:::.

-  : :.

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

-  : :.:::.

-  : :::.

-  : .

-  : .
    1 : Contínuo,
    0 : Preso,
    -1 : Inváldo,
    -2 : ,
    -3 : .
    .

-  : .
    0 : 

-  : :.
    -1 : ,
    -2 : .

-  : :.
    -1 : ,
    -2 : .

-  : .
    -2 : ,
    1 : ,
    0 : .

-  : 

-  : .

-  : . o : .

-  : :. .

-  : . .

:. :

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
- `randomColor(mn,max)` : .
- `trgger(commande)` : .
- `trggerValue(commande)` : .
- `rnd(valeur,[decmal])` : .
- `odd(valeur)` : . .
- `medan(commande1,commande2…​.commandeN)` : .
- `avg(commande1,commande2…​.commandeN) `: .
- `_op(,value)` :  : .
- `_beween(,Começo,end)` :  : 1530), `Começo=temps`, `end=temps`. .
- `_dff(Data1,Data2[,format, rnd])` : ::. . . :55::55:00,s)`. . Vs pvez auss utlser `dhms` qu rernera pas exemple `7j 2h 5mn 46s`. . : `_dff(2020-02-21 20:55::01:14,df, 4)`.
- `formatTme()` : Perme de formater le rer d'une chane `##`.
- `floor(/60)` : .
- `convertDuraton(s)` : .

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
- **Varável**  : .
- ****  : .
- **Cenas**  : .  : . . . 
- **Pare**  : Pára o scrpt.
- **Esperar**  : .
- **Va o projeo**  : .
- **àdconar um regstro**  : Permte adconar uma mensagem no log.
- **Crar mensagem**  : .
- **àctvar / Desactvar Hde / Show equpamentos**  : .
- **àplcar**  : . .
    .
    . .
- ****  : .
- **Reornar um texto / um dado**  : Reorna um texto  um valor para uma nteração por exemplo.
- **ícone**  : .
- **àvso**  : . .
- ****  : .
- **Relatóro**  : . . .
- **clur Blocoo IN / à agendado**  : àpagar a programação de todos os Blocoos dentro e à Cenáro.
- **Evento**  : .
- ****  : .
- ****  : .

### Modelo cenáro

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
- **Baxar** : .

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
