
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


export function formatomon(x){  //215451.325145 --> 215,451.32
    if(x<0) x*0-1;
    var num=Math.round(x*100)/100;
    var cad=num.toString();
    if(!cad.includes('.')) cad=cad+'.00';
    cad=cad.split('').reverse();
    if(cad[1]=='.') cad.unshift('0');
    var arr=[]; var c=0;
    for(var i=0;i<cad.length;i++){
          if(i<3) arr.push(cad[i]);
        else {
            if(c<3){ arr.push(cad[i]); c++; }
            else { arr.push(','); c=0; i--; }
        }
    }
    return arr.reverse().join('');
}

