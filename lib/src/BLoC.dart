
import 'dart:collection';
import 'dart:convert';

import 'package:elmenu/src/APIConfig.dart';
import 'package:http/http.dart';
import 'package:rxdart/rxdart.dart';

import 'DataModels/Menu.dart';


class BLoC{

  final _menuSubject = BehaviorSubject<UnmodifiableListView<MenuItem>>();
  List<MenuItem> _menuItems = List<MenuItem>();
  Stream<UnmodifiableListView<MenuItem>> get Menu => _menuSubject.stream;
  
  Future<Null> _getMenuItemsList() async{
    List<MenuItem> list;
    Response respose = await get(Uri.encodeFull(APIConfig.getMenu));
    final jsonResponse = json.decode(respose.body);
    final jsonMenu = jsonResponse['menu'] as List;
    list = jsonMenu.map<MenuItem>((e) => MenuItem.fromJsom(e)).toList();
    _menuItems = list;
  }
  
  BLoC(){
    _getMenuItemsList().then((_){
      _menuSubject.add(UnmodifiableListView(_menuItems));
    });
  }
  
}