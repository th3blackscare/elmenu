import 'dart:collection';

import 'package:elmenu/src/DataModels/Offer.dart';
import 'package:flutter/material.dart';

import '../BLoC.dart';

class OffersPageView extends StatefulWidget {
  BLoC bLoC;


  OffersPageView({Key key,this.bLoC});
  @override
  _OffersPageViewState createState() => _OffersPageViewState();
}

class _OffersPageViewState extends State<OffersPageView> {

  @override
  Widget build(BuildContext context) {
    return Container(
      child: Column(
        children: [
          Expanded(
            child: StreamBuilder<UnmodifiableListView<offer>>(
                stream: widget.bLoC.Offer,
                initialData: UnmodifiableListView<offer>([]),
                builder: (context, snapshot) => ListView(
                    children: snapshot.data.map(menu_item).toList(),
          )))
        ],
      ),
    );
  }

  Widget menu_item(offer item){
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
                tag: item.offer_title,
                child: Container(
                  width: 120,
                  height: 120,
                  decoration: BoxDecoration(
                      borderRadius: BorderRadius.only(topLeft: Radius.circular(20),bottomLeft: Radius.circular(20)),
                      image: DecorationImage(
                          image: NetworkImage(item.offer_photo),
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
                                ' ${item.offer_title} ',
                                overflow: TextOverflow.fade,
                                softWrap: false,
                                style: TextStyle(color: Theme.of(context).primaryColor,fontSize: 14,fontWeight: FontWeight.bold,fontFamily: 'Cairo'),
                              ),
                            ),
                            SizedBox(
                              height: 7,
                            ),
                            Text(
                              item.offer_detalis,
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
                              item.is_discount=='0'?"price: ${item.offer_price}":"Discount: ${item.offer_discount}",
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
