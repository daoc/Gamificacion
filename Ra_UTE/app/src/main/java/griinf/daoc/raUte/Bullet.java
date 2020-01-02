package griinf.daoc.raUte;

import android.content.Context;
import android.os.Vibrator;

import com.threed.jpct.Object3D;
import com.threed.jpct.Primitives;
import com.threed.jpct.RGBColor;
import com.threed.jpct.SimpleVector;
import com.threed.jpct.World;

public class Bullet {
    private Object3D bala;
    private World world;
    private SimpleVector motion;
    private final MainActivity master;

    private final float max_distance = 80f;
    private final float SPEED = 0.5f;
    private final float REACH = 0.5f;

    public Bullet(MainActivity master) {
        this.master = master;
        init();
    }

    private void init() {
        bala = Primitives.getSphere(1f);
        bala.setAdditionalColor(RGBColor.RED);
        bala.setCollisionMode(Object3D.COLLISION_CHECK_SELF);
        bala.build();
        motion = new SimpleVector();
        motion.z = SPEED;
        //world.addObject(bala);
    }

    public Object3D getObj() {
        return bala;
    }

    public void move() {
        if(!master.bullet_fired) return;
        float distance = bala.getTransformedCenter().distance(SimpleVector.ORIGIN);
        bala.translate(motion);
        int crashed =  bala.checkForCollision(motion, REACH);
        if(crashed != Object3D.NO_OBJECT) {
            System.out.println("******BUM*******");
            master.vibrator.vibrate(500);
            master.monster_hit = true;
            resetBullet();
        }
        if(distance > max_distance) {
            resetBullet();
        }

    }

    private void resetBullet() {
        //bala.setVisibility(false);
        //bala.setOrigin(SimpleVector.ORIGIN);
        bala.clearTranslation();
        master.bullet_fired = false;
    }

}
