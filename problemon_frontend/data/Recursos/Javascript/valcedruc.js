function Vcedula(cedval)
    {
       var numc;
       var aux;
       var b;
       var sump= 0;
       var sumip=0;
       var i=9;
       var j;
       var ims=0;
       var v;
       var cedula=cedval;
       for( j=0;j<10;j+=2)
       {
           b=2*cedula[j];
           if(b>9)
               b=b-9;
           sump+=b;
       }
       for( j=1;j<8;j+=2)
       {
           b=cedula[j] * 1;
           sumip+=b;
       }
       aux=sump+sumip;
       ims=aux-(aux%10)+10;
       v=ims-aux;
       if(v==10)
           v=0;
       if(v==cedula[9])
           return true;
       else
           return false;
   }

function Vruc(ruc)
{
   var aux;
   var coef=[];
   var acum=0;
   if(ruc[2]==9){
       console.log("entro a 9")
       coef=[4,3,2,7,6,5,4,3,2]
       for (var i=0;i < 9; i++){
           acum+=parseInt(coef[i])*parseInt(ruc[i]);
       }
       console.log("acum "+acum);
       aux=acum%11;
       console.log("aux "+aux);
       if(aux==0){
           if(ruc[9]==aux)
               return true;
           else
               return false;
       }else{
           aux=11-aux;
           console.log("aux=11-aux "+aux);
           if(ruc[9]==aux)
               return true;
           else
               return false;
       }
   }else if(ruc[2]==6){
    console.log("entro a 6")
       coef=[3,2,7,6,5,4,3,2]
       for (var i=0;i < 8; i++){
           acum+=parseInt(coef[i])*parseInt(ruc[i]);
       }
       console.log("acum "+acum);
       aux=acum%11;
       console.log("aux "+aux);
       if(aux==0){
           if(ruc[8]==aux)
               return true;
           else
               return false;
       }else{
           aux=11-aux;
           console.log("aux=11-aux "+aux);
           if(ruc[8]==aux)
               return true;
           else
               return false;
       }
   }else if(ruc[2]<6){
       if(Vcedula(ruc)){
           return true;
       }else{
           return false;
       }
   }else{
       return false;
   }
}