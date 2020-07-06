import 'dart:io' show Platform;
import 'package:elmenu/src/BLoC.dart';
import 'package:elmenu/src/FCM.dart';
import 'package:elmenu/src/pages/HomePage.dart';
import 'package:flare_splash_screen/flare_splash_screen.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter/widgets.dart';

void main() {
  BLoC bloc = BLoC();
  runApp(MyApp());
}


class MyApp extends StatelessWidget {
  BLoC bloc = BLoC();
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'Crinkle',
      home: SplashScreen.navigate(
        name: 'assets/splash.flr',
        next: (context) => AfterSplash(bLoC: bloc,),
        until: () {
          // ignore: unnecessary_statements
          bloc;
          return Future.delayed(Duration(seconds: 3,));
          },
        // animations name
        startAnimation: "gadeve",
        backgroundColor: Colors.white,
      ),
    );
  }
}

class AfterSplash extends StatelessWidget {
  BLoC bLoC;

  AfterSplash({Key key, this.bLoC}); // This widget is the root of your application.
  @override
  Widget build(BuildContext context) {
    (Platform.isWindows)?null:PushNotificationsManager().init();
//    SystemChrome.setSystemUIOverlayStyle(
//      SystemUiOverlayStyle(
//          statusBarColor: Colors.white,
//        statusBarIconBrightness: Brightness.dark
//      ),
//    );
    SystemChrome.setEnabledSystemUIOverlays([]);
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        primarySwatch: Colors.red,
        primaryColor: Colors.red,
        visualDensity: VisualDensity.adaptivePlatformDensity,
      ),
      home: HomePageView(bloc: bLoC,),
    );
  }
}

