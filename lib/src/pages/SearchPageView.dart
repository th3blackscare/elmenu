import 'dart:async';
import 'dart:collection';

import 'package:elmenu/src/DataModels/Menu.dart';
import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';

class SearchPageView extends SearchDelegate<MenuItem>{
  final Stream<UnmodifiableListView<MenuItem>> menuItems;


  SearchPageView({Key key,this.menuItems});

  @override
  List<Widget> buildActions(BuildContext context) {
    return[
      IconButton(
        icon: FaIcon(FontAwesomeIcons.backspace),
        onPressed: (){
          query = '';
        },
      )
    ];
  }

  @override
  Widget buildLeading(BuildContext context) {
    return IconButton(
      icon: FaIcon(FontAwesomeIcons.chevronRight),
      onPressed: (){
        close(context, null);
      },
    );
  }

  @override
  Widget buildResults(BuildContext context) {
    return Container();
  }

  @override
  Widget buildSuggestions(BuildContext context) {
    return StreamBuilder<UnmodifiableListView<MenuItem>>(
      stream: menuItems,
      builder: (context, AsyncSnapshot<UnmodifiableListView<MenuItem>> snapshot){
        if(!snapshot.hasData){
          return Center(
            child: Text('لا يوجد بيانات'),
          );
        }

        final results = snapshot.data.where((s) => (s.item_name.toLowerCase().contains(query))||(s.category.toLowerCase().contains(query)));

        return ListView(
          children: results.map<Widget>((e) => menu_item(e, context)).toList(),
        );
      },
    );
  }

  Widget menu_item(MenuItem item, BuildContext context){
    return Padding(
      padding: const EdgeInsets.only(top:7.0,bottom: 7.0, left: 8.0,right: 8.0),
      child: InkWell(
        child: Container(
          decoration: BoxDecoration(
            borderRadius: BorderRadius.all(Radius.circular(20)),
            color: Colors.white,
            boxShadow: [BoxShadow(
              color: Colors.grey.withOpacity(0.5),
              spreadRadius: 1,
              blurRadius: 10,
              offset: Offset(0,3),
            )],
          ),
          child: Row(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Hero(
                tag: item.item_id,
                child: Container(
                  width: 120,
                  height: 120,
                  decoration: BoxDecoration(
                      borderRadius: BorderRadius.only(topLeft: Radius.circular(20),bottomLeft: Radius.circular(20)),
                      image: DecorationImage(
                          image: NetworkImage(item.item_photo),
                          fit: BoxFit.cover
                      )
                  ),
                ),
              ),
              Container(
                width: MediaQuery.of(context).size.width-140,
                height: 120,
                child: Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 15,vertical: 10),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.center,
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    mainAxisSize: MainAxisSize.max,
                    children: <Widget>[
                      Expanded(
                        flex: 3,
                        child: Column(
                          children: <Widget>[
                            Container(
                              decoration: BoxDecoration(
                                  border: Border.all(width: 3,color: Colors.red.shade300),
                                  borderRadius: BorderRadius.all(Radius.circular(5))
                              ),
                              child: Text(
                                ' ${item.item_name} ',
                                overflow: TextOverflow.fade,
                                softWrap: false,
                                style: TextStyle(color: Theme.of(context).primaryColor,fontSize: 14,fontWeight: FontWeight.bold,fontFamily: 'Cairo'),
                              ),
                            ),
                            SizedBox(
                              height: 7,
                            ),
                            Text(
                              item.item_details,
                              overflow: TextOverflow.fade,
                              softWrap: true,
                              maxLines: 2,
                              style: Theme.of(context).textTheme.caption,
                            ),
                            SizedBox(
                              height: 4,
                            ),
                          ],
                        ),
                      ),
                      SizedBox(width: 15,),
                      Expanded(
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: <Widget>[
                            Text(
                              "price: ${item.item_price}",
                              //overflow: TextOverflow.fade,
                              maxLines: 1,
                              softWrap: false,
                              style: TextStyle(color: Colors.grey,fontFamily: 'cairo',fontWeight: FontWeight.bold),
                            ),
                            Text(
                              "Press here for More Details",
                              overflow: TextOverflow.fade,
                              maxLines: 1,
                              softWrap: false,
                              style: TextStyle(color: Colors.blue,fontFamily: 'cairo',fontWeight: FontWeight.bold,fontSize: 10),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

}