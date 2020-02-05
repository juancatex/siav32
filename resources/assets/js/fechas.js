
export function fechalat(ff){  //2019-03-05  -->  05/03/2019
    if(!ff) return;
    return ff.substr(8,2)+'/'+ff.substr(5,2)+'/'+ff.substr(0,4);
}

export function fechames(ff){  //2019-03-05  -->  05/Mar/2019
    if(!ff) return;
    var mes=['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
    var mm=ff.substr(5,2)-1;
    return ff.substr(8,2)+'/'+mes[mm]+'/'+ff.substr(0,4);
}

export function fechadia(ff){  //2019-03-05  -->  Ma, 05/Mar/2019
    if(!ff) return;
    var mes=['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
    //var semana=['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
    var semana=['Do','Lu','Ma','Mi','Ju','Vi','Sá'];
    var lafecha=ff.substr(5,2)+' '+ff.substr(8,2)+' '+ff.substr(0,4);
    var dd=new Date(lafecha).getDay();
    var mm=ff.substr(5,2)-1;
    return semana[dd]+', '+ff.substr(8,2)+'/'+mes[mm]+'/'+ff.substr(0,4);
}

export function periodo(ff){
    if(!ff) return;
    var mes=['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
    var mm=ff.substr(5,2)-1;
    return mes[mm]+'/'+ff.substr(0,4);
}