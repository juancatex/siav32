
export function formatomon(x){  //215451.325145 --> 215,451.32
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

