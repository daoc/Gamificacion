package griinf.daoc.raUte;

import android.opengl.GLSurfaceView;
import android.support.annotation.Nullable;

import com.threed.jpct.Camera;
import com.threed.jpct.FrameBuffer;
import com.threed.jpct.Light;
import com.threed.jpct.Loader;
import com.threed.jpct.Logger;
import com.threed.jpct.Matrix;
import com.threed.jpct.Object3D;
import com.threed.jpct.Primitives;
import com.threed.jpct.RGBColor;
import com.threed.jpct.SimpleVector;
import com.threed.jpct.Texture;
import com.threed.jpct.TextureManager;
import com.threed.jpct.World;
import com.threed.jpct.util.BitmapHelper;
import com.threed.jpct.util.MemoryHelper;

import java.io.InputStream;
import java.util.Random;

import javax.microedition.khronos.egl.EGLConfig;
import javax.microedition.khronos.opengles.GL10;

public class MyRenderer implements GLSurfaceView.Renderer {
    Random rnd = new Random();

    enum Type3D {
        _3DS,
        _OBJ
    }

    private FrameBuffer fb;
    private World world;
    private Camera cam;
    private SimpleVector origin;
    private Object3D bala;

    //private Object3D cube;
    private int fps = 0;
    private long time = System.currentTimeMillis();
    private MainActivity master;
    private Character charizard;

    public MyRenderer(MainActivity master) {
        this.master = master;
        buildWorld();
    }

    @Override
    public void onSurfaceChanged(GL10 gl, int w, int h) {
        if (fb != null) {
            fb.dispose();
        }
        fb = new FrameBuffer(gl, w, h);
    }

    @Override
    public void onSurfaceCreated(GL10 gl, EGLConfig config) {}

    @Override
    public void onDrawFrame(GL10 gl) {
//        if (master.touchTurn != 0) {
//            charizard.getObj().rotateY(master.touchTurn);
//            master.touchTurn = 0;
//        }
//
//        if (master.touchTurnUp != 0) {
//            charizard.getObj().rotateX(master.touchTurnUp);
//            master.touchTurnUp = 0;
//        }
//
        charizard.turn(master.touchTurn, master.touchTurnUp);

        master.touchTurn = 0; master.touchTurnUp = 0;

        charizard.move();

        float proportion = 0.6f;
        cam.rotateCameraX(master.deltaRotationVector[1] * proportion);
        cam.rotateCameraY(-master.deltaRotationVector[0] * proportion);
//        cam.rotateCameraZ(-master.deltaRotationVector[2]);

        SimpleVector camDir = cam.getDirection();
        bala.align(cam);
        bala.clearTranslation();
        bala.translate(camDir.x, camDir.y, camDir.z+10);

        fb.clear();
        world.renderScene(fb);
        world.draw(fb);
        fb.display();

        if (System.currentTimeMillis() - time >= 1000) {
            Logger.log(fps + "fps");
            fps = 0;
            time = System.currentTimeMillis();
        }
        fps++;
    }

    private void buildWorld() {
        world = new World();
        cam = world.getCamera();
        origin = new SimpleVector(cam.getPosition());
        //origin.set(cam.getPosition());
        cam.moveCamera(Camera.CAMERA_MOVEOUT, 50);

        world.setAmbientLight(50, 50, 50);
        Light sun = new Light(world);
        sun.setIntensity(200, 200, 200);
        //sun.setPosition(new SimpleVector(origin.x, origin.y - 100, origin.z - 100));
        sun.setPosition(cam.getPosition());

        bala = Primitives.getSphere(2f);
        bala.setAdditionalColor(RGBColor.RED);
        bala.translate(25, 0, 0);
        bala.build();
        world.addObject(bala);

        //loadTexture(R.drawable.monster, "monster");
        loadTexture(R.drawable.charizard, "charizard");

        charizard = new Character(master, Character.Type3D._OBJ, R.raw.charizard, 0.9f, null, "charizard");
        world.addObject(charizard.getObj());
        //cube = loadModel(R.raw.charizard, 0.9f, Type3D._OBJ, "charizard");
        //cube = loadModel(R.raw.pokeball, 0.1f, Type3D._3DS, null);
        //cube = loadModel(R.raw.other_pokeball, 5f, Type3D._OBJ, null);
        //cube = loadModel(R.raw.monster, 0.7f, Type3D._3DS, "monster");
        //cube.build();
        //world.addObject(cube);


        //cam.lookAt(charizard.getObj().getTransformedCenter());

//        SimpleVector sv = new SimpleVector();
//        //sv.set(charizard.getObj().getTransformedCenter());
//        sv.set(origin);
//        sv.y -= 100;
//        sv.z -= 100;
//        sun.setPosition(sv);

        MemoryHelper.compact();
    }

    private void loadTexture(int ressource, String name) {
        if(!TextureManager.getInstance().containsTexture(name)) {
            Texture texture = new Texture(BitmapHelper.rescale(BitmapHelper.convert(master.getResources().getDrawable(ressource)), 512, 512));
            TextureManager.getInstance().addTexture(name, texture);
        }
    }

    private Object3D loadModel(int ressource, float scale, Type3D type, @Nullable String texture) {
        InputStream stream = master.getResources().openRawResource(ressource);

        Object3D[] model = null;
        if (type == Type3D._3DS) {
            model = Loader.load3DS(stream, scale);
        } else if (type == Type3D._OBJ) {
            model = Loader.loadOBJ(stream, null, scale);
        }
        Object3D o3d = new Object3D(0);
        Object3D temp = null;
        for (int i = 0; i < model.length; i++) {
            temp = model[i];
            temp.setCenter(SimpleVector.ORIGIN);
            temp.rotateX((float) (-.5 * Math.PI));
            temp.rotateMesh();
            temp.setRotationMatrix(new Matrix());
            o3d = Object3D.mergeObjects(o3d, temp);
            o3d.build();
        }

        if(texture != null) {
            o3d.setTexture(texture);
        }

        return o3d;
    }
}