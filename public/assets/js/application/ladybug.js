function prefix(uri){
    try{
        return VIE.Util.toCurie("<" + uri + ">", false, namespaces);
    }
    catch(Error){
        return uri;
    }
}

function unprefix(curie){
    try{
        var uri =  VIE.Util.toUri( curie , namespaces);
        uri= uri.substring(1, uri.length-1);
        return uri;
    }
    catch(Error){
        return curie;
    }
}
